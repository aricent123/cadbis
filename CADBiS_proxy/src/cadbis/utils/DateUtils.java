package cadbis.utils;

import java.util.Calendar;

public class DateUtils {
	
	public static int getDOW()
	{
		int dow = Calendar.getInstance().get(Calendar.DAY_OF_WEEK);
		dow = (dow == 1)? 7 : dow-1;
		return dow;
	}
	
	public static int getDOM()
	{
		return Calendar.getInstance().get(Calendar.DAY_OF_MONTH);
	}
	
}
