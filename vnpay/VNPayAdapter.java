package dashspace.fun.car_rental_server.infrastructure.adapter;

import dashspace.fun.car_rental_server.domain.payment.dto.request.InitPaymentRequest;
import dashspace.fun.car_rental_server.domain.payment.dto.response.InitPaymentResponse;
import dashspace.fun.car_rental_server.infrastructure.config.prop.VNPayProperties;
import dashspace.fun.car_rental_server.infrastructure.constant.Symbol;
import dashspace.fun.car_rental_server.infrastructure.constant.VNPayParams;
import dashspace.fun.car_rental_server.infrastructure.service.CrytoService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;

import java.net.URLEncoder;
import java.nio.charset.StandardCharsets;
import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
public class VNPayAdapter {

    private final CrytoService crytoService;
    private final VNPayParamsBuilder pramasBuilder;

    private final VNPayProperties vnPayProperties;

    public InitPaymentResponse initPayment(InitPaymentRequest request) {
        Map<String, String> params = pramasBuilder.build(request);
        var initPaymentUrl = buildInitPaymentUrl(params);

        return InitPaymentResponse.builder()
                .vnpUrl(initPaymentUrl)
                .build();
    }

    public boolean verifyIpn(Map<String, String> params) {
        var reqSecureHash = params.get(VNPayParams.SECURE_HASH);
        params.remove(VNPayParams.SECURE_HASH);
        params.remove(VNPayParams.SECURE_HASH_TYPE);

        var secureHash = buildSecureHash(params, sortFieldNames(params));

        return reqSecureHash.equals(secureHash);
    }

    private String buildInitPaymentUrl(Map<String, String> params) {
        var fieldNames = sortFieldNames(params);
        var secureHash = buildSecureHash(params, fieldNames);
        var query = buildQuery(params, fieldNames, secureHash);
        return vnPayProperties.getPayUrl() + Symbol.QUERY_SEPARATOR + query;
    }

    private List<String> sortFieldNames(Map<String, String> params) {
        return params.keySet().stream()
                .sorted()
                .toList();
    }

    private String buildSecureHash(Map<String, String> params, List<String> fieldNames) {
        var payload = fieldNames.stream()
                .map(fn -> fn + Symbol.EQUAL + encodeUSASCII(params.get(fn)))
                .collect(Collectors.joining(Symbol.AND));

        return crytoService.sign(payload);
    }

    private String buildQuery(Map<String, String> params, List<String> fieldNames, String secureHash) {
        var query = fieldNames.stream()
                .map(fn -> encodeUSASCII(fn) + Symbol.EQUAL + encodeUSASCII(params.get(fn)))
                .collect(Collectors.joining(Symbol.AND));

        return query + Symbol.AND + VNPayParams.SECURE_HASH + Symbol.EQUAL + secureHash;
    }

    private String encodeUSASCII(String value) {
        return URLEncoder.encode(value, StandardCharsets.US_ASCII);
    }
}
