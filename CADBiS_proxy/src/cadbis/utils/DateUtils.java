package cadbis.utils;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

public class DateUtils {
	
	/**
	 * Returns day of week
	 * @return
	 */
	public static int getDOW()
	{
		int dow = Calendar.getInstance().get(Calendar.DAY_OF_WEEK);
		dow = (dow == 1)? 7 : dow-1;
		return dow;
	}
	
	/**
	 * Returns day of month
	 * @return
	 */
	public static int getDOM()
	{
		return Calendar.getInstance().get(Calendar.DAY_OF_MONTH);
	}

	/**
	 * Returns date for sql
	 * @return String date
	 */
	public static String getDateForSql()
	{
		return new SimpleDateFormat("yyyy-MM-dd").format(new Date());
	}	
	
	/**
	 * Returns days count in current month
	 * @return
	 */
	public static int getDaysInMonth()
	{
		return Calendar.getInstance().getActualMaximum(Calendar.DAY_OF_MONTH);
	}
		
}
