package dashspace.fun.car_rental_server.domain.payment.service;

import dashspace.fun.car_rental_server.domain.payment.dto.request.InitPaymentRequest;
import dashspace.fun.car_rental_server.domain.payment.dto.response.InitPaymentResponse;
import dashspace.fun.car_rental_server.infrastructure.adapter.VNPayAdapter;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;

import java.util.Map;

@Service
@RequiredArgsConstructor
public class VNPayService implements PaymentService {

    private final VNPayAdapter adapter;

    public InitPaymentResponse init(InitPaymentRequest request) {
        return adapter.initPayment(request);
    }

    public boolean verifyIpn(Map<String, String> params) {
        return adapter.verifyIpn(params);
    }
}
