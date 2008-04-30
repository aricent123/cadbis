package cadbis;

import java.util.HashMap;
import java.util.List;

import cadbis.bl.Packet;
import cadbis.db.PacketDAO;

public class PacketsTodayLimits {
	protected HashMap<Integer, Long> dayTrafficLimits = null;
	protected HashMap<Integer, Long> weekTrafficLimits = null;
	protected HashMap<Integer, Long> monthTrafficLimits = null;
	
	
	protected void recalcTrafficLimits()
	{
		dayTrafficLimits = new HashMap<Integer, Long>();
		weekTrafficLimits = new HashMap<Integer, Long>();
		monthTrafficLimits = new HashMap<Integer, Long>();
		PacketDAO dao = new PacketDAO();
		Long maximumMonthTraffic =  (Long)dao.getSingleValueByQuery("select value from `cadbis_config` where name='max_month_traffic'","value");
		Long maximumWeekTraffic = maximumMonthTraffic / 4L;
		Long maximumDayTraffic = maximumMonthTraffic / 30L;		
		List<Packet> packets = dao.getItemsByQuery("select p.*, count(u.uid) as users_count from `packets` p inner join `users` u on u.gid = p.gid group by u.gid");
		
		
		// month limits
		Long monthSum = 0L; 
		for(int i = 0; i< packets.size(); ++i)
			monthSum += packets.get(i).getMonth_traffic_limit() * packets.get(i).getUsers_count();
		for(int i = 0; i< packets.size(); ++i)
			monthTrafficLimits.put(packets.get(i).getGid(), Math.round((100.0/(double)monthSum)*(double)packets.get(i).getMonth_traffic_limit()));
		// week limits
		Long weekSum = 0L; 
		for(int i = 0; i< packets.size(); ++i)
			weekSum += packets.get(i).getWeek_traffic_limit() * packets.get(i).getUsers_count();
		for(int i = 0; i< packets.size(); ++i)
			weekTrafficLimits.put(packets.get(i).getGid(), Math.round((100.0/(double)weekSum)*(double)packets.get(i).getWeek_traffic_limit()));

		// day limits
		Long daySum = 0L; 
		for(int i = 0; i< packets.size(); ++i)
			daySum += packets.get(i).getDay_traffic_limit() * packets.get(i).getUsers_count();
		for(int i = 0; i< packets.size(); ++i)
			dayTrafficLimits.put(packets.get(i).getGid(), Math.round((100.0/(double)daySum)*(double)packets.get(i).getDay_traffic_limit()));
		
		
	}
	
	public Long getPacketMonthTrafficLimit(Integer gid)
	{
		if(monthTrafficLimits == null)
			recalcTrafficLimits();
		return monthTrafficLimits.get(gid);
	}
	
	public Long getPacketWeekTrafficLimit(Integer gid)
	{
		if(weekTrafficLimits == null)
			recalcTrafficLimits();
		return weekTrafficLimits.get(gid);
	}
	public Long getPacketDayTrafficLimit(Integer gid)
	{
		if(dayTrafficLimits == null)
			recalcTrafficLimits();
		return dayTrafficLimits.get(gid);
	}	
}
