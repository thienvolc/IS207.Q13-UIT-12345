package dashspace.fun.car_rental_server.infrastructure.service;

import dashspace.fun.car_rental_server.app.aop.BusinessException;
import dashspace.fun.car_rental_server.domain.common.constant.ResponseCode;
import dashspace.fun.car_rental_server.infrastructure.constant.DefaultValue;
import dashspace.fun.car_rental_server.infrastructure.util.EncodingUtil;
import jakarta.annotation.PostConstruct;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Service;

import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;

@Service
public class CrytoService {

    private final Mac mac = Mac.getInstance(DefaultValue.HMAC_SHA512);

    @Value("${payment.providers.vnpay.hash-secret")
    private String hashSecret;

    public CrytoService() throws NoSuchAlgorithmException {
    }

    @PostConstruct
    public void init() throws InvalidKeyException {
        SecretKeySpec secretKeySpec = new SecretKeySpec(hashSecret.getBytes(), DefaultValue.HMAC_SHA512);
        mac.init(secretKeySpec);
    }

    public String sign(String data) {
        try {
            byte[] hmacBytes = mac.doFinal(data.getBytes());
            return EncodingUtil.bytesToHexString(hmacBytes);
        } catch (Exception ex) {
            throw new BusinessException(ResponseCode.VNPAY_SIGNING_FAILED);
        }
    }
}
