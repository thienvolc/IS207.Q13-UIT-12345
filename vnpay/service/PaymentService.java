package dashspace.fun.car_rental_server.domain.payment.service;

import dashspace.fun.car_rental_server.domain.payment.dto.request.InitPaymentRequest;
import dashspace.fun.car_rental_server.domain.payment.dto.response.InitPaymentResponse;

public interface PaymentService {
    InitPaymentResponse init(InitPaymentRequest initPaymentRequest);
}
