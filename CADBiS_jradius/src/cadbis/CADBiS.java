package cadbis;


import java.util.Calendar;
import java.util.HashMap;


import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.bl.Packet;
import cadbis.bl.User;
import cadbis.db.ActionDAO;
import cadbis.db.PacketDAO;
import cadbis.db.UserDAO;
import cadbis.exc.CADBiSException;
import cadbis.jradius.JRadiusConfigurator;
import cadbis.proxy.Proxy;

public class CADBiS extends CADBiSDaemon{
	
	protected PacketsTodayLimits packetLimits = null;
	protected HashMap<String, String> activeSessions = null;
	protected PacketsTodayLimits dayLimits = null;
	
	protected final Logger logger = LoggerFactory.getLogger(getClass());
	private static CADBiS instance = null;
	
	private CADBiS()
	{
		super("CADBiS",Integer.valueOf(JRadiusConfigurator.getInstance().getProperty("cadbis_daemon_period")));
		logger.info("Instantiating the proxy server...");
		new Proxy().start();		
		createObjects();
	}
	
	public static CADBiS getInstance(){
		if(instance == null)
			instance = new CADBiS();
		return instance;
	}
	
	private void createObjects()
	{
		activeSessions = new HashMap<String, String>();
		dayLimits = new PacketsTodayLimits();
	}
	
	@Override
	protected void daemonize() {
		logger.info("Waking up, killing inactive users...");
		KillInactiveUsers();
	}		
	
	protected void KillInactiveUsers()
	{
		String sTout = JRadiusConfigurator.getInstance().getProperty("session_maxinacct_time");
		if(!sTout.isEmpty()){
			Long timeout = Long.valueOf(sTout);		
			new ActionDAO().execSql(String.format("update `actions` set `terminate_cause`='Inactive-Request', `stop_time`=NOW() where `terminate_cause`='Online' and `last_change`< UNIX_TIMESTAMP(NOW()) - %d ",timeout));
		}
	}
	
	public Long getConnectedCount(User user)
	{
		Long res = (Long)new PacketDAO().getSingleValueByQuery(
				String.format("select count(*) as count from `actions` where terminate_cause = 'Online' and user = '%s'", user.getUser()), 
				"count");
		if(res == null)
			res = 0L;
		return res;		
	}
	
	public Long getPacketUsageCount(User user)
	{
		Long res = (Long)new PacketDAO().getSingleValueByQuery(
				String.format("select count(*) as count from `actions` where terminate_cause = 'Online' and gid= %d", user.getGid()), 
				"count");
		if(res == null)
			res = 0L;
		return res;
	}	
	
	public boolean checkAccessNow(String login, String framedIp, String clientIp)
	{
		
		User user = new UserDAO().getByLoginWithStats(login);
		PacketDAO dao = new PacketDAO();
		Packet userpacket = dao.getPacketWithStats(user.getGid());
		Checker checker = new Checker(userpacket, user);
		
		if(userpacket!=null)
		{
			try{
				checker.checkBlocked();
				checker.checkTrafficLimits();
				checker.checkSimultaneous_use(getConnectedCount(user));
				checker.checkAccessTime(); 
				checker.checkTrafficLimits(user.getMtraffic(),user.getWtraffic(),
													user.getDtraffic(), user.getTtraffic());
				
				checker.checkTimeLimits(user.getMtime(),user.getWtime(), 
													user.getDtime(),user.getTtime());
				checker.checkPacketUsage(getPacketUsageCount(user));
				
				checker.checkDayTrafficLimit(
						dao.getDayTraffic(userpacket.getGid()),
						dayLimits.getPacketDayTrafficLimit(user.getGid()));
			}
			catch(CADBiSException e)
			{
				logger.warn("Exceed access error: " + e.getClass() + ", error= "+ e.getMessage());
				new Notifier(login,"","",e.getUniformMessage()).start();
				return false;
			}
			catch(Exception e)
			{
				logger.error("Error occured while access checking: " +e.getMessage());
				e.printStackTrace();
			}

			
			return true;
		}
		return false;
	}
	
	public String generateUniqueId(String sessionID)
	{
		String res = "";
		Long timestamp = Calendar.getInstance().getTimeInMillis();
		byte[] hash = sessionID.getBytes();//MD5.md5(sessionID.toString().getBytes());
		for(int i=0;i<sessionID.length();++i)
			res += Integer.toHexString(hash[i]);
		res += "-" + timestamp.toString();
		return res;
	}
	
	protected String getUniqueId(String sessionId)
	{
		if(activeSessions.containsKey(sessionId))
			return activeSessions.get(sessionId);
		return "";
	}
	
	public void SessionStart(String sessionId, String login, String clientIP, String framedIP, Integer nasPort)
	{
		String uniqueId = generateUniqueId(sessionId);
		logger.info("Starting session for "+login+" with uniqueid='"+uniqueId+"', clientIP="+clientIP+", framedIp="+framedIP);
		if(activeSessions.containsKey(uniqueId))
			activeSessions.remove(uniqueId);
		activeSessions.put(sessionId, uniqueId);
		User user = new UserDAO().getByLogin(login);
		new ActionDAO().execSql(String.format("insert into `actions`(user,gid,id,unique_id,start_time,stop_time,ip,call_from,last_change, terminate_cause, port)" +
				"values('%s',%d,'%s','%s',NOW(),NOW(),'%s','%s',UNIX_TIMESTAMP(NOW()),'Online',%d)",user.getUser(),user.getGid(),sessionId,uniqueId,framedIP,clientIP,nasPort));
	}
	
	public void SessionStop(String sessionId, String login, long sessionTime, long outputOctets, long inputOctets, String terminateCause)
	{
		String uniqueId = getUniqueId(sessionId);
		logger.info("Stopping session for "+login+" with uniqueid='"+uniqueId+"', sessiontime="+sessionTime+", outputOctets="+outputOctets+", inputOctets="+inputOctets+", terminateCause="+terminateCause);
		if(uniqueId.length()>0)
			new ActionDAO().execSql(String.format("update `actions` set in_bytes=%d, out_bytes=%d," +
					"time_on=%d,terminate_cause='%s',stop_time=NOW() where unique_id = '%s'",
					inputOctets, outputOctets, sessionTime, terminateCause, uniqueId));	
		activeSessions.remove(sessionId);
	}
	
	public void SessionAlive(String sessionId, String login, String framedIP, String clientIP, Integer nasPort, long sessionTime, long outputOctets, long inputOctets)
	{		
		String uniqueId = getUniqueId(sessionId);
		logger.info("Alive session for "+login+" with uniqueid='"+uniqueId+"', sessiontime="+sessionTime+", outputOctets="+outputOctets+", inputOctets="+inputOctets);
		if(uniqueId.length()>0)
		{
			new ActionDAO().execSql(String.format("update `actions` set in_bytes=%d, out_bytes=%d,time_on=%d where unique_id = '%s'",
					inputOctets, outputOctets, sessionTime, uniqueId));
			if(!checkAccessNow(login, framedIP, clientIP))
			{
				logger.info("Killing user '"+login+"' with ip='"+clientIP+"'");
				new Killer(login,framedIP, clientIP, nasPort).start();
			}
		}
	}	
	
}
