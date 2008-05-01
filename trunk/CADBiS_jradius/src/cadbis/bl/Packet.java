package cadbis.bl;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.exc.DayTimeLimitExceedException;
import cadbis.exc.DayTrafficLimitExceedException;
import cadbis.exc.MonthTimeLimitExceedException;
import cadbis.exc.MonthTrafficLimitExceedException;
import cadbis.exc.PacketUsageExceedException;
import cadbis.exc.TotalTimeLimitExceedException;
import cadbis.exc.TotalTrafficLimitExceedException;
import cadbis.exc.WeekTimeLimitExceedException;
import cadbis.exc.WeekTrafficLimitExceedException;
import cadbis.exc.WrongAccessTimeException;

public class Packet implements BusinessObject{
	private Integer gid;
	private String packet;
	private Integer blocked;
	private Long total_time_limit;
	private Long month_time_limit;
	private Long week_time_limit;
	private Long day_time_limit;
	private Long total_traffic_limit;
	private Long month_traffic_limit;
	private Long week_traffic_limit;
	private Long day_traffic_limit;
	private String login_time;
	private Integer port_limit;
	private Integer rang;
	private Integer exceed_times;
	private Integer users_count = 0;
	private Integer simuluse_sum  = 0;
	protected final Logger logger = LoggerFactory.getLogger(getClass());
	
	public String[][] getPerstistenceFields() {
		String[][] fields = {
					{"gid",					"Integer"},
					{"packet",				"String"},
					{"blocked",				"Integer"},
					{"total_time_limit",	"Long"},
					{"month_time_limit",	"Long"},
					{"week_time_limit",		"Long"},
					{"day_time_limit",		"Long"},
					{"total_traffic_limit",	"Long"}, 
					{"month_traffic_limit",	"Long"},
					{"week_traffic_limit",	"Long"},
					{"day_traffic_limit",	"Long"},
					{"login_time",			"String"},					
					{"port_limit",			"Integer"},
					{"users_count",			"Integer"},
					{"rang",				"Integer"},
					{"exceed_times",		"Integer"},
					{"simuluse_sum",		"Integer"}
					};
		return fields;
	}



	public Integer getGid() {
		return gid;
	}



	public void setGid(Integer gid) {
		this.gid = gid;
	}



	public String getPacket() {
		return packet;
	}



	public void setPacket(String packet) {
		this.packet = packet;
	}



	public Integer getBlocked() {
		return blocked;
	}



	public void setBlocked(Integer blocked) {
		this.blocked = blocked;
	}



	public Object getTotal_time_limit() {
		return total_time_limit;
	}



	public void setTotal_time_limit(Object total_time_limit) {
		this.total_time_limit = (Long)total_time_limit;
	}



	public Long getMonth_time_limit() {
		return month_time_limit;
	}



	public void setMonth_time_limit(Long month_time_limit) {
		this.month_time_limit = month_time_limit;
	}



	public Long getWeek_time_limit() {
		return week_time_limit;
	}



	public void setWeek_time_limit(Long week_time_limit) {
		this.week_time_limit = week_time_limit;
	}



	public Long getDay_time_limit() {
		return day_time_limit;
	}



	public void setDay_time_limit(Long day_time_limit) {
		this.day_time_limit = day_time_limit;
	}


	public Long getTotal_traffic_limit() {
		return total_traffic_limit;
	}

	public void setTotal_traffic_limit(Long total_traffic_limit) {
		this.total_traffic_limit = total_traffic_limit;
	}

	public Long getMonth_traffic_limit() {
		return month_traffic_limit;
	}

	public void setMonth_traffic_limit(Long month_traffic_limit) {
		this.month_traffic_limit = month_traffic_limit;
	}

	public Long getWeek_traffic_limit() {
		return week_traffic_limit;
	}

	public void setWeek_traffic_limit(Long week_traffic_limit) {
		this.week_traffic_limit = week_traffic_limit;
	}

	public Long getDay_traffic_limit() {
		return day_traffic_limit;
	}

	public void setDay_traffic_limit(Long day_traffic_limit) {
		this.day_traffic_limit = day_traffic_limit;
	}

	public String getLogin_time() {
		return login_time;
	}

	public void setLogin_time(String login_time) {
		this.login_time = login_time;
	}

	public void setTotal_time_limit(Long total_time_limit) {
		this.total_time_limit = total_time_limit;
	}	

	public Integer getPort_limit() {
		return port_limit;
	}

	public void setPort_limit(Integer port_limit) {
		this.port_limit = port_limit;
	}
	
	public Integer getUsers_count() {
		return users_count;
	}

	public void setUsers_count(Integer users_count) {
		this.users_count = users_count;
	}
	
	public Integer getRang() {
		return rang;
	}

	public void setRang(Integer rang) {
		this.rang = rang;
	}

	public Integer getExceed_times() {
		return exceed_times;
	}

	public void setExceed_times(Integer exceed_times) {
		this.exceed_times = exceed_times;
	}	
	
	public Integer getSimuluse_sum() {
		return simuluse_sum;
	}

	public void setSimuluse_sum(Integer simuluse_sum) {
		this.simuluse_sum = simuluse_sum;
	}	
	
	
	public Integer getSummarRang() {
		return rang*simuluse_sum;
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
	
	public boolean checkAccessTime() throws WrongAccessTimeException
	{
		boolean access = false;
		if(login_time != null && !login_time.isEmpty())
		{
			String[] periods = login_time.split(",");			
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
				throw new WrongAccessTimeException();
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
		throws MonthTrafficLimitExceedException, WeekTrafficLimitExceedException, 
		DayTrafficLimitExceedException, TotalTrafficLimitExceedException
	{
		if(!(cur_mtraffic <= month_traffic_limit || month_traffic_limit==0))
			throw new MonthTrafficLimitExceedException();
		if(!(cur_wtraffic <= week_traffic_limit  || week_traffic_limit==0))
			throw new WeekTrafficLimitExceedException();
		if(!(cur_dtraffic <= day_traffic_limit || day_traffic_limit==0))
			throw new DayTrafficLimitExceedException();
		if(!(cur_ttraffic <= total_traffic_limit || total_traffic_limit==0))
			throw new TotalTrafficLimitExceedException();
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
		throws MonthTimeLimitExceedException, WeekTimeLimitExceedException, 
				DayTimeLimitExceedException, TotalTimeLimitExceedException
	{
		if(!(cur_mtime <= month_time_limit || month_time_limit==0))
			throw new MonthTimeLimitExceedException();
		if(!(cur_wtime <= week_time_limit || week_time_limit==0))
			throw new WeekTimeLimitExceedException();
		if(!(cur_dtime <= day_time_limit || day_time_limit==0))
			throw new DayTimeLimitExceedException();
		if(!(cur_ttime <= total_time_limit || total_time_limit==0))
			throw new TotalTimeLimitExceedException();
	}

	
	public void checkPacketUsage(long usage_count) throws PacketUsageExceedException
	{
		if(this.port_limit <= usage_count)
			throw new PacketUsageExceedException();
	}

}
