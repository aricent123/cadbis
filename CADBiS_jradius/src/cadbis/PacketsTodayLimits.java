package cadbis;

import java.math.BigInteger;
import java.util.HashMap;
import java.util.List;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.bl.Packet;
import cadbis.db.PacketDAO;
import cadbis.utils.DateUtils;

public class PacketsTodayLimits {
	protected final Logger logger = LoggerFactory.getLogger(getClass());
	protected HashMap<Integer, Double> packetsCoefs = null;
	protected HashMap<Integer, Long> dayTrafficLimits = null;
	protected HashMap<Integer, Long> monthTrafficLimits = null;
	protected Long restDaysCount = 0L;
	protected Long usedMonthTraffic = 0L;
	protected Long maximumMonthTraffic = 0L;
	protected Long restMonthTraffic = 0L;
	private Long allowedDayTraffic = 0L;
	
	protected void recalcTrafficLimits()
	{
		packetsCoefs = new HashMap<Integer, Double>();
		dayTrafficLimits = new HashMap<Integer, Long>();
		monthTrafficLimits = new HashMap<Integer, Long>();
		PacketDAO dao = new PacketDAO();
		BigInteger tmpMaximumMonthTraffic =  (BigInteger)dao.getSingleValueByQuery("select value from `cadbis_config` where name='max_month_traffic'","value");
		usedMonthTraffic = dao.getMonthTraffic();
		restDaysCount = Long.valueOf((DateUtils.getDaysInMonth() - DateUtils.getDOM()))+1;		
		if(usedMonthTraffic == null)
			usedMonthTraffic= 0L;
		List<Packet> packets = dao.getPacketsWithStats();
		if(tmpMaximumMonthTraffic!=null){
			maximumMonthTraffic = tmpMaximumMonthTraffic.longValue();
			restMonthTraffic = maximumMonthTraffic.longValue() - usedMonthTraffic.longValue();
			allowedDayTraffic  = (restMonthTraffic) / restDaysCount;
			
			double SumOfRangs = 0; 
			for(int i = 0; i< packets.size(); ++i)
				SumOfRangs += (double)packets.get(i).getSummarRang();
			for(int i = 0; i< packets.size(); ++i)
				packetsCoefs.put(packets.get(i).getGid(), ((double)packets.get(i).getSummarRang())/SumOfRangs);
			
			for(int i = 0; i< packets.size(); ++i)
			{
				Long dayLimit = Math.round((double)allowedDayTraffic * packetsCoefs.get(packets.get(i).getGid()));
				Long restPacketMonthTraffic = dayLimit*restDaysCount;
				monthTrafficLimits.put(packets.get(i).getGid(),restPacketMonthTraffic);
				if((packets.get(i).getExceed_times()+1)*dayLimit<=restPacketMonthTraffic)
					dayLimit *= packets.get(i).getExceed_times()+1; 
				dayTrafficLimits.put(packets.get(i).getGid(),dayLimit);
			}
		
		logger.info("maximumMonthTraffic = " + maximumMonthTraffic.longValue()/1024/1024+" Mb");
		logger.info("usedMonthTraffic = " + usedMonthTraffic.longValue()/1024/1024+" Mb");
		logger.info("restDaysCount = " + restDaysCount);
		logger.info("restMonthTraffic="+ restMonthTraffic/1024/1024+" Mb");
		logger.info("allowedDayTraffic="+ allowedDayTraffic/1024/1024+" Mb");
		for(int i = 0; i< packets.size(); ++i)
			logger.info("packet "+packets.get(i).getGid()+" '"+packets.get(i).getPacket()+"', coef="+packetsCoefs.get(packets.get(i).getGid())+",daylimit=" + dayTrafficLimits.get(packets.get(i).getGid())/1024/1024+"Mb of "+monthTrafficLimits.get(packets.get(i).getGid())/1024/1024+"Mb rest");
		
		}		
	}
	
	public Long getPacketDayTrafficLimit(Integer gid)
	{
		if(dayTrafficLimits == null)
			recalcTrafficLimits();
		return dayTrafficLimits.get(gid);
	}

	public Long getRestDaysCount() {
		return restDaysCount;
	}

	public Long getUsedMonthTraffic() {
		return usedMonthTraffic;
	}

	public Long getRestMonthTraffic() {
		return restMonthTraffic;
	}

	public Long getAllowedDayTraffic() {
		return allowedDayTraffic;
	}	
}
