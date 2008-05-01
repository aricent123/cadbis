package cadbis;

import java.math.BigDecimal;
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
	
	protected void recalcTrafficLimits()
	{
		packetsCoefs = new HashMap<Integer, Double>();
		dayTrafficLimits = new HashMap<Integer, Long>();
		monthTrafficLimits = new HashMap<Integer, Long>();
		PacketDAO dao = new PacketDAO();
		BigInteger maximumMonthTraffic =  (BigInteger)dao.getSingleValueByQuery("select value from `cadbis_config` where name='max_month_traffic'","value");
		Long usedMonthTraffic = dao.getMonthTraffic();
		Long restDaysCount = Long.valueOf((DateUtils.getDaysInMonth() - DateUtils.getDOM()))+1;		
		if(usedMonthTraffic == null)
			usedMonthTraffic= 0L;
		List<Packet> packets = dao.getItemsWithStats();
		if(maximumMonthTraffic!=null){
			Long restMonthTraffic = maximumMonthTraffic.longValue() - usedMonthTraffic.longValue();
			Long allowedDayTraffic = (restMonthTraffic) / restDaysCount;
			
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
				if(packets.get(i).getExceed_times()*dayLimit<=restPacketMonthTraffic)
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
}
