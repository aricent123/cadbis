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

public class CADBiS {
	
	protected HashMap<String, String> activeSessions = new HashMap<String, String>();
	protected final Logger logger = LoggerFactory.getLogger(getClass());
	private static CADBiS instance = null;
	private CADBiS()
	{
		
	}
	
	public static CADBiS getInstance(){
		if(instance == null)
			instance = new CADBiS();
		return instance;
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
	
	public boolean checkAccessNow(String login)
	{
		User user = new UserDAO().getByLoginWithStats(login);
		PacketDAO dao = new PacketDAO();
		Packet userpacket = dao.getItemByQuery(String.format("select * from `packets` where gid = %d",user.getGid()));
		if(userpacket!=null)
		{
			try{
				userpacket.checkAccessTime(); 
				userpacket.checkTrafficLimits(user.getMtraffic(),user.getWtraffic(),
													user.getDtraffic(), user.getTtraffic());
				
				userpacket.checkTimeLimits(user.getMtime(),user.getWtime(), 
													user.getDtime(),user.getTtime());
				userpacket.checkPacketUsage(getPacketUsageCount(user));
				userpacket.checkSimultaneouseUse(getConnectedCount(user));
			}
			catch(Exception e)
			{
				logger.warn("Exceed access error: " + e.getClass());
				return false;
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
	
	public void SessionStart(String sessionId, String login, String clientIP, String framedIP)
	{
		String uniqueId = generateUniqueId(sessionId);
		logger.info("Starting session for "+login+" with uniqueid='"+uniqueId+"', clientIP="+clientIP+", framedIp="+framedIP);
		if(activeSessions.containsKey(uniqueId))
			activeSessions.remove(uniqueId);
		activeSessions.put(sessionId, uniqueId);
		User user = new UserDAO().getByLogin(login);
		new ActionDAO().execSql(String.format("insert into `actions`(user,gid,unique_id,start_time,ip,call_from,last_change, terminate_cause)" +
				"values('%s',%d,'%s',NOW(),'%s','%s',UNIX_TIMESTAMP(NOW()),'Online')",user.getUser(),user.getGid(),uniqueId,framedIP,clientIP));
	}
	
	public void SessionStop(String sessionId, String login, long sessionTime, long outputOctets, long inputOctets, String terminateCause)
	{
		String uniqueId = getUniqueId(sessionId);
		logger.info("Stopping session for "+login+" with uniqueid='"+uniqueId+"', sessiontime="+sessionTime+", outputOctets="+outputOctets+", inputOctets="+inputOctets+", terminateCause="+terminateCause);
		if(uniqueId.length()>0)
		{
			// closing session
		}
		new ActionDAO().execSql(String.format("update `actions` set in_bytes=%d, out_bytes=%d," +
									"time_on=%d,terminate_cause='%s',stop_time=NOW() where unique_id = '%s'",
									inputOctets, outputOctets, sessionTime, terminateCause, uniqueId));	
		activeSessions.remove(sessionId);
	}
	
	public void SessionAlive(String sessionId, String login, long sessionTime, long outputOctets, long inputOctets)
	{		
		String uniqueId = getUniqueId(sessionId);
		logger.info("Alive session for "+login+" with uniqueid='"+uniqueId+"', sessiontime="+sessionTime+", outputOctets="+outputOctets+", inputOctets="+inputOctets);
		if(uniqueId.length()>0)
		{
			new ActionDAO().execSql(String.format("update `actions` set in_bytes=%d, out_bytes=%d,time_on=%d where unique_id = '%s'",
					inputOctets, outputOctets, sessionTime, uniqueId));	
		}
	}	
	
}
