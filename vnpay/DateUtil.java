package dashspace.fun.car_rental_server.infrastructure.util;

import java.text.SimpleDateFormat;
import java.time.LocalDate;
import java.time.temporal.ChronoUnit;
import java.util.Calendar;

public class DateUtil {

    private static final SimpleDateFormat VNPAY_DATE_FORMAT = new SimpleDateFormat("yyyyMMddHHmmss");

    public static long getDiffInDays(LocalDate date1, LocalDate date2) {
        return ChronoUnit.DAYS.between(date1, date2);
    }

    public static String formatVnTime(Calendar vnCalendar) {
        return VNPAY_DATE_FORMAT.format(vnCalendar.getTime());
    }
}
