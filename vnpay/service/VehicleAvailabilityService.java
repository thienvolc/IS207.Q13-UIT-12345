package dashspace.fun.car_rental_server.domain.rental.service;

import dashspace.fun.car_rental_server.app.aop.BusinessException;
import dashspace.fun.car_rental_server.domain.common.constant.ResponseCode;
import dashspace.fun.car_rental_server.domain.rental.entity.VehicleAvailability;
import dashspace.fun.car_rental_server.domain.rental.repository.VehicleAvailabilityRepository;
import dashspace.fun.car_rental_server.infrastructure.util.DateUtil;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;

import java.time.LocalDate;
import java.util.List;

@Service
@RequiredArgsConstructor
public class VehicleAvailabilityService {

    private static final int MAX_RENTAL_DAYS = 30;

    private final VehicleAvailabilityRepository repository;

    public List<VehicleAvailability> checkAvailabilityForRental(Integer vehicleId,
                                                                LocalDate pickupDate,
                                                                LocalDate returnDate) {

        int rentalDays = (int) DateUtil.getDiffInDays(pickupDate, returnDate);
        if (rentalDays > MAX_RENTAL_DAYS) {
            throw new BusinessException(ResponseCode.RENTAL_DAYS_EXCEEDED);
        }

        var availableDays = repository.findAndLockVehicleAvailabilities(
                vehicleId,
                pickupDate,
                returnDate.minusDays(1)
        );

        if (availableDays.isEmpty() || availableDays.size() < rentalDays) {
            throw new BusinessException(ResponseCode.VEHICLE_BUSY);
        }

        return availableDays;
    }

    public void saveAll(List<VehicleAvailability> availableDays) {
        repository.saveAll(availableDays);
    }

    public List<VehicleAvailability> getRange(Integer vehicleId,
                                              LocalDate pickupDate,
                                              LocalDate returnDate) {

        return repository.findRange(vehicleId, pickupDate, returnDate.minusDays(1));
    }
}
