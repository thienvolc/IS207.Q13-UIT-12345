package dashspace.fun.car_rental_server.infrastructure.adapter;

import dashspace.fun.car_rental_server.domain.common.constant.Locale;
import dashspace.fun.car_rental_server.domain.payment.dto.request.InitPaymentRequest;
import dashspace.fun.car_rental_server.infrastructure.config.prop.VNPayProperties;
import dashspace.fun.car_rental_server.infrastructure.constant.VNPayParams;
import dashspace.fun.car_rental_server.infrastructure.util.DateUtil;
import dashspace.fun.car_rental_server.infrastructure.util.I18nUtil;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Component;

import java.math.BigDecimal;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;
import java.util.TimeZone;

@Component
@RequiredArgsConstructor
class VNPayParamsBuilder {

    private static final BigDecimal DEFAUTL_MULTIPLIER = new BigDecimal("100");
    private static final String VERSION = "2.1.0";
    private static final String COMMAND = "pay";
    private static final String CURRENCY = "VND";
    private static final String ORDER_TYPE = "other";

    private final VNPayProperties vnPayProperties;

    public Map<String, String> build(InitPaymentRequest request) {
        var amount = request.amount().multiply(DEFAUTL_MULTIPLIER).toString();
        var txnRef = request.txnRef();
        var ipAddress = request.ipAddress();
        var returnUrl = buildReturnUrl(txnRef);
        var orderInfo = buildPaymentDetail(request);

        var vnCalendar = Calendar.getInstance(TimeZone.getTimeZone("Etc/GMT+7"));
        var createDate = DateUtil.formatVnTime(vnCalendar);
        vnCalendar.add(Calendar.MINUTE, vnPayProperties.getPaymentTimeout());
        var expireDate = DateUtil.formatVnTime(vnCalendar);

        var params = new HashMap<String, String>();
        params.put(VNPayParams.VERSION, VERSION);
        params.put(VNPayParams.COMMAND, COMMAND);

        params.put(VNPayParams.TMN_CODE, vnPayProperties.getTerminalCode());
        params.put(VNPayParams.AMOUNT, amount);
        params.put(VNPayParams.CURRENCY_CODE, CURRENCY);

        params.put(VNPayParams.TXN_REF, txnRef);
        params.put(VNPayParams.RETURN_URL, returnUrl);

        params.put(VNPayParams.ORDER_TYPE, ORDER_TYPE);
        params.put(VNPayParams.ORDER_INFO, orderInfo);

        params.put(VNPayParams.CREATE_DATE, createDate);
        params.put(VNPayParams.EXPIRE_DATE, expireDate);

        params.put(VNPayParams.LOCALE, Locale.VIETNAM.getCode());
        params.put(VNPayParams.IP_ADDRESS, ipAddress);

        return params;
    }

    private String buildReturnUrl(String txnRef) {
        return vnPayProperties.getReturnUrl().formatted(txnRef);
    }

    private String buildPaymentDetail(InitPaymentRequest request) {
        return I18nUtil.get("rental.payment.detail.format").formatted(request.txnRef());
    }
}
