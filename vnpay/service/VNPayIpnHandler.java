package dashspace.fun.car_rental_server.domain.payment.service;

import dashspace.fun.car_rental_server.app.aop.BusinessException;
import dashspace.fun.car_rental_server.domain.common.constant.ResponseCode;
import dashspace.fun.car_rental_server.domain.payment.dto.response.IpnResponse;
import dashspace.fun.car_rental_server.domain.rental.service.RentalService;
import dashspace.fun.car_rental_server.infrastructure.constant.VNPayParams;
import dashspace.fun.car_rental_server.infrastructure.constant.VnpIpnResponseConst;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;

import java.util.Map;

@Service
@RequiredArgsConstructor
public class VNPayIpnHandler implements IpnHandler {

    private final VNPayService vnPayService;
    private final RentalService rentalService;

    @Override
    public IpnResponse process(Map<String, String> params) {
        if (!vnPayService.verifyIpn(params)) {
            return VnpIpnResponseConst.SIGNATURE_FAILED;
        }

        var response = VnpIpnResponseConst.SUCCESS;
        try {
            processPaymentSuccess(params);
        } catch (BusinessException ex) {
            response = getIpnResponseFromErrorCode(ex.getResponseCode());
        } catch (Exception ex) {
            response = VnpIpnResponseConst.UNKNOWN_ERROR;
        }
        return response;
    }

    private void processPaymentSuccess(Map<String, String> params)
            throws NumberFormatException, BusinessException {

        var txnRef = params.get(VNPayParams.TXN_REF);
        var rentalId = Integer.parseInt(txnRef);
        rentalService.markPaid(rentalId);
    }

    private IpnResponse getIpnResponseFromErrorCode(ResponseCode code) {
        if (code == ResponseCode.RENTAL_NOT_FOUND) {
            return VnpIpnResponseConst.ORDER_NOT_FOUND;
        }
        return VnpIpnResponseConst.UNKNOWN_ERROR;
    }
}
