package dashspace.fun.car_rental_server.domain.rental.service;

import dashspace.fun.car_rental_server.app.aop.BusinessException;
import dashspace.fun.car_rental_server.domain.common.constant.ResponseCode;
import dashspace.fun.car_rental_server.domain.payment.dto.request.InitPaymentRequest;
import dashspace.fun.car_rental_server.domain.payment.service.PaymentService;
import dashspace.fun.car_rental_server.domain.rental.constant.RentalStatus;
import dashspace.fun.car_rental_server.domain.rental.constant.VehicleAvailabilityStatus;
import dashspace.fun.car_rental_server.domain.rental.dto.request.RentalRequest;
import dashspace.fun.car_rental_server.domain.rental.dto.response.RentalResponse;
import dashspace.fun.car_rental_server.domain.rental.dto.response.RentalStatusResponse;
import dashspace.fun.car_rental_server.domain.rental.entity.Rental;
import dashspace.fun.car_rental_server.domain.rental.mapper.RentalMapper;
import dashspace.fun.car_rental_server.domain.rental.repository.RentalRepository;
import dashspace.fun.car_rental_server.domain.rental.util.VehicleLocationUtil;
import dashspace.fun.car_rental_server.domain.user.service.UserService;
import dashspace.fun.car_rental_server.domain.vehicle.constant.VehicleStatus;
import dashspace.fun.car_rental_server.domain.vehicle.service.VehicleService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.time.LocalDate;

@Service
@RequiredArgsConstructor
public class RentalService {

    private final VehicleAvailabilityService availabilityService;
    private final VehicleService vehicleService;
    private final UserService userService;
    private final PricingService pricingService;
    private final PaymentService paymentService;

    private final RentalMapper mapper;
    private final RentalRepository repository;

    @Transactional
    public RentalResponse rent(RentalRequest request, Integer userId, String ipAddress) {
        validateRequest(request);
        validateVehicle(request.vehicleId(), userId);

        var rental = createRental(request, userId);
        var initPaymentRequest = InitPaymentRequest.builder()
                .userId(rental.getRenter().getId())
                .txnRef(rental.getId().toString())
                .amount(rental.getTotalAmount())
                .requestId(request.requestId())
                .ipAddress(ipAddress)
                .build();
        var initPaymentResponse = paymentService.init(initPaymentRequest);
        var rentalDto = mapper.toDto(rental);

        return RentalResponse.builder()
                .rental(rentalDto)
                .payment(initPaymentResponse)
                .build();
    }

    private void validateRequest(RentalRequest request) {
        var pickupDate = request.pickupDate();
        var returnDate = request.returnDate();
        var currentDate = LocalDate.now();

        if (pickupDate.isBefore(currentDate) || returnDate.isBefore(pickupDate)) {
            throw new BusinessException(ResponseCode.PICKUP_DATE_INVALID);
        }
    }

    private void validateVehicle(Integer vehicleId, Integer userId) {
        var vehicle = vehicleService.tryFindVehicleById(vehicleId);

        if (vehicle.getStatus() != VehicleStatus.ACTIVE) {
            throw new BusinessException(ResponseCode.VEHICLE_NOT_ACTIVE);
        }

        if (vehicleService.isVehicleOwnedByUser(vehicleId, userId)) {
            throw new BusinessException(ResponseCode.VEHICLE_RENTED_BY_OWNER);
        }
    }

    private Rental createRental(RentalRequest request, Integer userId) {
        var vehicle = vehicleService.tryFindVehicleById(request.vehicleId());
        var user = userService.tryFindUserById(userId);

        var pickupDate = request.pickupDate();
        var returnDate = request.returnDate();

        var availableDays = availabilityService.checkAvailabilityForRental(vehicle.getId(), pickupDate, returnDate);
        var addressDetail = VehicleLocationUtil.extract(vehicle.getLocation());
        var price = pricingService.calculate(availableDays);
        var rental = Rental.builder()
                .vehicle(vehicle)
                .renter(user)
                .rentalCode(request.requestId())
                .note(request.note())
                .pickupDate(pickupDate)
                .returnDate(returnDate)
                .totalAmount(price.totalAmount())
                .depositAmount(price.depositAmount())
                .currency(price.currency())
                .pickupLocation(addressDetail)
                .returnLocation(addressDetail)
                .build();

        availableDays.forEach(day -> day.setStatus(VehicleAvailabilityStatus.HELD));
        availabilityService.saveAll(availableDays);
        return repository.save(rental);
    }

    @Transactional
    public void markPaid(Integer rentalId) throws BusinessException {
        var rental = repository.findById(rentalId)
                .orElseThrow(() -> new BusinessException(ResponseCode.RENTAL_NOT_FOUND));

        rental.setStatus(RentalStatus.PENDING);

        var availableDays = availabilityService.getRange(
                rental.getVehicle().getId(),
                rental.getPickupDate(),
                rental.getReturnDate()
        );
        availableDays.forEach(day -> day.setStatus(VehicleAvailabilityStatus.HELD));
        availabilityService.saveAll(availableDays);
        repository.save(rental);
    }

    public RentalStatusResponse getRentalStatus(Integer rentalId) {
        var rental = repository.findById(rentalId)
                .orElseThrow(() -> new BusinessException(ResponseCode.RENTAL_NOT_FOUND));
        return mapper.toRentalStatusResponse(rental);
    }
}
