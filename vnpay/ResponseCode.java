package dashspace.fun.car_rental_server.domain.common.constant;

import dashspace.fun.car_rental_server.infrastructure.util.I18nUtil;
import lombok.AllArgsConstructor;
import lombok.Getter;
import org.springframework.http.HttpStatus;

import static org.springframework.http.HttpStatus.*;

@Getter
@AllArgsConstructor
public enum ResponseCode {
    TOKEN_MISSING("ERR_TOKEN_MISSING", "token.missing", UNAUTHORIZED),
    EMAIL_ALREADY_EXISTS("ERR_EMAIL_ALREADY_EXISTS", "email.already_exists", CONFLICT),
    EMAIL_NOT_VERIFIED("ERR_EMAIL_NOT_VERIFIED", "email.not_verified", BAD_REQUEST),
    BAD_CREDENTIALS("ERR_BAD_CREDENTIALS", "bad_credentials", UNAUTHORIZED),
    USERNAME_NOT_FOUND("ERR_USERNAME_NOT_FOUND", "username.not_found", NOT_FOUND),
    ACCESS_DENIED("ERR_ACCESS_DENIED", "access_denied", FORBIDDEN),
    INTERNAL_EXCEPTION("ERR_INTERNAL_EXCEPTION", "internal_server_error", INTERNAL_SERVER_ERROR),
    OTP_INVALID("ERR_OTP_INVALID", "otp.invalid", BAD_REQUEST),
    OTP_EXPIRED("ERR_OTP_EXPIRED", "otp.expired", BAD_REQUEST),
    OTP_NOT_VERIFIED("ERR_OTP_NOT_VERIFIED", "otp.not_verified", BAD_REQUEST),
    SUCCESS("SUCCESS", "request.ok", OK),
    LOGOUT_ALL("SUCCESS_LOGOUT_ALL", "logout.all", OK),
    PASSWORD_SAME_AS_OLD("ERR_PASSWORD_SAME_AS_OLD", "password.update.same_as_old", BAD_REQUEST),
    USER_NOT_FOUND("ERR_EXISTING_USER_NOT_FOUND", "user.not_found", NOT_FOUND),
    IMAGE_UPLOAD_FAILED("ERR_IMAGE_UPLOAD_FAILED", "image.upload.failed", INTERNAL_SERVER_ERROR),
    LICENSE_PLATE_ALREADY_EXISTS("ERR_LICENSE_PLATE_ALREADY_EXISTS", "license.plate.already_exists", CONFLICT),
    VEHICLE_ALREADY_APPROVED("ERR_VEHICLE_ALREADY_APPROVED", "vehicle.already_approved", BAD_REQUEST),
    VEHICLE_ALREADY_REJECTED("ERR_VEHICLE_ALREADY_REJECTED", "vehicle.already_rejected", BAD_REQUEST),
    FORGOT_PASSWORD_RESET("FORGOT_PASSWORD_RESET", "forgot.password.reset", OK),
    PICKUP_DATE_INVALID("ERR_RENTAL_PICKUP_DATE_INVALID", "rental.pickup.date.invalid", BAD_REQUEST),
    VEHICLE_NOT_FOUND("ERR_VEHICLE_NOT_FOUND", "vehicle.not_found", NOT_FOUND),
    VEHICLE_NOT_ACTIVE("ERR_VEHICLE_NOT_ACTIVE", "vehicle.not_active", BAD_REQUEST),
    VEHICLE_RENTED_BY_OWNER("ERR_VEHICLE_RENTED_BY_OWNER", "vehicle.rented_by_owner", BAD_REQUEST),
    RENTAL_DAYS_EXCEEDED("ERR_RENTAL_DAYS_EXCEEDED", "rental.days.exceeded", BAD_REQUEST),
    VEHICLE_BUSY("ERR_VEHICLE_BUSY", "vehicle.busy", BAD_REQUEST),
    VNPAY_SIGNING_FAILED("ERR_VNPAY_SIGNING_FAILED", "vnpay.signing.failed", INTERNAL_SERVER_ERROR),
    RENTAL_NOT_FOUND("ERR_RENTAL_NOT_FOUND", "rental.not_found", NOT_FOUND);

    private final String code;
    private final String defaultMessage;
    private final HttpStatus status;

    public String getDefaultMessage() {
        return I18nUtil.get(defaultMessage);
    }
}
