package dashspace.fun.car_rental_server.domain.payment.service;

import dashspace.fun.car_rental_server.domain.payment.dto.response.IpnResponse;

import java.util.Map;

public interface IpnHandler {
    IpnResponse process(Map<String, String> params);
}
