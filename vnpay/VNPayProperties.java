package dashspace.fun.car_rental_server.infrastructure.config.prop;

import lombok.Getter;
import lombok.Setter;
import org.springframework.boot.context.properties.ConfigurationProperties;
import org.springframework.context.annotation.Configuration;
import org.springframework.stereotype.Component;

@Getter
@Setter
@Component
@Configuration
@ConfigurationProperties(prefix = "payment.providers.vnpay")
public class VNPayProperties {
    private String terminalCode;
    private String hashSecret;
    private String payUrl;
    private String returnUrl;
    private String username;
    private int paymentTimeout;
}
