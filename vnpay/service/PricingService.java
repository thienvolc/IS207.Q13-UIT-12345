package dashspace.fun.car_rental_server.domain.rental.service;

import dashspace.fun.car_rental_server.domain.common.constant.Currency;
import dashspace.fun.car_rental_server.domain.rental.dto.RentalPrice;
import dashspace.fun.car_rental_server.domain.rental.entity.VehicleAvailability;
import org.springframework.stereotype.Service;

import java.math.BigDecimal;
import java.util.List;

@Service
public class PricingService {

    private static final BigDecimal DEPOSIT_RATE = new BigDecimal("0.4");

    public RentalPrice calculate(List<VehicleAvailability> availableDays) {
        var totalAmount = availableDays.stream()
                .map(VehicleAvailability::getPrice)
                .reduce(BigDecimal.ZERO, BigDecimal::add);

        var depositAmount = totalAmount.multiply(DEPOSIT_RATE);
        var subtotal = totalAmount.subtract(depositAmount);

        return RentalPrice.builder()
                .totalAmount(totalAmount)
                .depositAmount(depositAmount)
                .subtotal(subtotal)
                .currency(Currency.VND.getValue())
                .build();
    }
}
