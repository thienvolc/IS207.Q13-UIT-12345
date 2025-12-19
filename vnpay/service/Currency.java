package dashspace.fun.car_rental_server.domain.common.constant;

import lombok.AllArgsConstructor;
import lombok.Getter;

@Getter
@AllArgsConstructor
public enum Currency {
    USD("USD"), VND("VND");

    private final String value;
}
