package cadbis;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.bl.Packet;
import cadbis.bl.User;
import cadbis.exc.CADBiSException;
import cadbis.exc.DayPacketTrafficLimitExceedException;
import cadbis.exc.DayTimeLimitExceedException;
import cadbis.exc.DayTrafficLimitExceedException;
import cadbis.exc.MonthTimeLimitExceedException;
import cadbis.exc.MonthTrafficLimitExceedException;
import cadbis.exc.PacketUsageExceedException;
import cadbis.exc.SimultaneousUseExceedException;
import cadbis.exc.TotalTimeLimitExceedException;
import cadbis.exc.TotalTrafficLimitExceedException;
import cadbis.exc.UserBlockedException;
import cadbis.exc.WeekTimeLimitExceedException;
import cadbis.exc.WeekTrafficLimitExceedException;
import cadbis.exc.WrongAccessTimeException;

/**
 * Class for checking the Internet access
 * @author smecsia
 *
 */
public class Checker {
	protected final Logger logger = LoggerFactory.getLogger(getClass());
	protected Packet packet;
	protected User user;
	
	public Checker(Packet packet, User user)
	{
		this.packet = packet;
		this.user = user;
	}
	
	protected boolean checkAccessByLoginTime(String dow, int fromh, int fromm, int toh, int tom)
	{
		String nowdow = new SimpleDateFormat("E", new Locale("US")).format(new Date()).substring(0, 2);
		if(nowdow.equals(dow))
		{
			int nowh = Calendar.getInstance().get(Calendar.HOUR_OF_DAY);
			int nowm = Calendar.getInstance().get(Calendar.MINUTE);
			if((fromh*60 + fromm <= nowh*60 + nowm) && (nowh*60 + nowm <= toh*60 + tom))
				return true;
		}
		return false;
	}
	
	public boolean checkAccessTime() throws CADBiSException
	{
		boolean access = false;
		if(this.packet.getLogin_time() != null && this.packet.getLogin_time().length()!=0)
		{
			String[] periods = this.packet.getLogin_time().split(",");			
			if(periods.length>0)
				for(int i=0;i<periods.length;++i)
				{
					try{			
						if(checkAccessByLoginTime(
								periods[i].substring(0,2),
								Integer.parseInt(periods[i].substring(2,4)),
								Integer.parseInt(periods[i].substring(4,6)),
								Integer.parseInt(periods[i].substring(7,9)),
								Integer.parseInt(periods[i].substring(9,11))))
							access=true;
					}
					catch(NumberFormatException e)
					{
						logger.error("Error parsing login time: " + e.getMessage());
					}
				}
			if(!access)
				throw new WrongAccessTimeException("Access denied outside of " + this.packet.getLogin_time());
		}
		return access;
	}
	
	
	
	/**
	 * @param cur_mtraffic
	 * @param cur_wtraffic
	 * @param cur_dtraffic
	 * @param cur_ttraffic
	 * @throws MonthTimeLimitExceedException
	 * @throws WeekTimeLimitExceedException
	 * @throws DayTimeLimitExceedException
	 * @throws TotalTimeLimitExceedException
	 */
	public void checkTrafficLimits(long cur_mtraffic, long cur_wtraffic, long cur_dtraffic, long cur_ttraffic) 
		throws CADBiSException
	{
		if(!(cur_mtraffic <= this.packet.getMonth_traffic_limit() || this.packet.getMonth_traffic_limit()==0))
			throw new MonthTrafficLimitExceedException(cur_mtraffic+" exceed maximum of "+this.packet.getMonth_traffic_limit());
		if(!(cur_wtraffic <= this.packet.getWeek_traffic_limit()  || this.packet.getWeek_traffic_limit()==0))
			throw new WeekTrafficLimitExceedException(cur_wtraffic+" exceed maximum of "+this.packet.getWeek_traffic_limit());
		if(!(cur_dtraffic <= this.packet.getDay_traffic_limit() || this.packet.getDay_traffic_limit()==0))
			throw new DayTrafficLimitExceedException(cur_dtraffic+" exceed maximum of "+this.packet.getDay_traffic_limit());
		if(!(cur_ttraffic <= this.packet.getTotal_traffic_limit() || this.packet.getTotal_traffic_limit()==0))
			throw new TotalTrafficLimitExceedException(cur_ttraffic+" exceed maximum of "+this.packet.getTotal_traffic_limit());
	}

	/**
	 * 
	 * @param cur_mtime
	 * @param cur_wtime
	 * @param cur_dtime
	 * @param cur_ttime
	 * @throws MonthTimeLimitExceedException
	 * @throws WeekTimeLimitExceedException
	 * @throws DayTimeLimitExceedException
	 * @throws TotalTimeLimitExceedException
	 */
	public void checkTimeLimits(long cur_mtime, long cur_wtime, long cur_dtime, long cur_ttime) 
		throws CADBiSException
	{
		if(!(cur_mtime <= this.packet.getMonth_time_limit() || this.packet.getMonth_time_limit()==0))
			throw new MonthTimeLimitExceedException(cur_mtime+" exceed maximum of "+this.packet.getMonth_time_limit());
		if(!(cur_wtime <= this.packet.getWeek_time_limit() || this.packet.getWeek_time_limit()==0))
			throw new WeekTimeLimitExceedException(cur_wtime+" exceed maximum of "+this.packet.getWeek_time_limit());
		if(!(cur_dtime <= this.packet.getDay_time_limit() || this.packet.getDay_time_limit()==0))
			throw new DayTimeLimitExceedException(cur_dtime+" exceed maximum of "+this.packet.getDay_time_limit());
		if(!(cur_ttime <= this.packet.getTotal_time_limit() || this.packet.getTotal_time_limit()==0))
			throw new TotalTimeLimitExceedException(cur_ttime+" exceed maximum of "+this.packet.getTotal_time_limit());
	}

	
	public void checkPacketUsage(long usage_count) throws CADBiSException
	{
		if(this.packet.getPort_limit()!=0 && this.packet.getPort_limit() <= usage_count)
			throw new PacketUsageExceedException(this.packet.getPort_limit()+" exceed maximum of "+usage_count);
	}

	public void checkDayTrafficLimit(Long dayTraffic, Long dayTrafficLimit) throws CADBiSException {
		if(dayTraffic >= dayTrafficLimit)
			throw new DayPacketTrafficLimitExceedException(dayTraffic+" exceed maximum of "+dayTrafficLimit); 
	}
	
	public void checkBlocked() throws CADBiSException {
		if(this.user.getBlocked() == 1)
			throw new UserBlockedException("User is blocked!");		
	}

	public void checkTrafficLimits() throws CADBiSException {
		if(!(user.getMtraffic() <= user.getMax_month_traffic()|| user.getMax_month_traffic()==0))
			throw new MonthTrafficLimitExceedException(user.getMtraffic()+" exceeds the value of "+user.getMax_month_traffic());
		if(!(user.getWtraffic() <= user.getMax_week_traffic()  || user.getMax_week_traffic()==0))
			throw new WeekTrafficLimitExceedException(user.getWtraffic()+" exceeds the value of "+user.getMax_week_traffic());
		if(!(user.getDtraffic() <= user.getMax_day_traffic() || user.getMax_day_traffic()==0))
			throw new DayTrafficLimitExceedException(user.getDtraffic()+" exceeds the value of "+user.getMax_day_traffic());
		if(!(user.getTtraffic() <= user.getMax_total_traffic() || user.getMax_total_traffic()==0))
			throw new TotalTrafficLimitExceedException(user.getTtraffic()+" exceeds the value of "+user.getMax_total_traffic());		
	}

	public void checkSimultaneous_use(Long connectedCount) throws CADBiSException {
		if(connectedCount >= user.getSimultaneous_use())
			throw new SimultaneousUseExceedException(connectedCount+" exceeds the value of "+user.getSimultaneous_use());		
	}
	
}
