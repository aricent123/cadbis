package cadbis.proxy;

import java.util.HashMap;
import java.util.List;

import cadbis.CADBiSDaemon;
import cadbis.proxy.bl.Protocol;
import cadbis.proxy.bl.UrlLogProtocol;
import cadbis.proxy.db.AbstractDAO;
import cadbis.proxy.db.UrlLogProtocolDAO;
import cadbis.proxy.utils.StringUtils;

public class Aggregator extends CADBiSDaemon {
	private static Aggregator instance = null;
		
	protected Aggregator() {
		super("Aggregator",Integer.valueOf(Configurator.getInstance().getProperty("aggregator_period")));
	}

	public static Aggregator getInstance(){
		if(instance == null)
			instance = new Aggregator();
		return (Aggregator)instance;
	}
	
	
	@SuppressWarnings("unchecked")
	protected HashMap<String, Integer> DefineUsersUids(List<UrlLogProtocol> logs, AbstractDAO dao)
	{
		HashMap<String, Integer> userUids = new HashMap<String, Integer>();
		for(int i=0;i<logs.size();++i)
		{		
			Integer uid = 0;
			String user = logs.get(i).getUser().toString();
			if(userUids.containsKey(user))
				uid = userUids.get(user);
			else
			{
				uid = (Integer)dao.getSingleValueByQuery("select uid from users where user = '"+user+"'", "uid");
				userUids.put(user, uid);
			}
		}
		return userUids;
	}
	
	@SuppressWarnings("unchecked")
	protected void UpdatePopularity(List<UrlLogProtocol> logs, AbstractDAO dao, HashMap<String, Integer> userUids)
	{
		for(int i=0;i<logs.size();++i)
		{
			int uid = userUids.get(logs.get(i).getUser().toString());
			// TODO: need to define the category automatically.
			int cid = 0;
			
			if(dao.getCountByQuery("select count(*) as count from `url_popularity` where url = '"+logs.get(i).getUrl()+"' and uid = "+uid+" and `year`=YEAR(CURDATE()) and `month`=MONTH(CURDATE())", "count")>0)
				dao.execSql("update `url_popularity` set count = count + "+logs.get(i).getCount().toString()+", length = length + "+logs.get(i).getLength().toString()+" where uid = "+uid+" and url='"+logs.get(i).getUrl().toString()+"';");
			else
				dao.execSql("insert into `url_popularity`(url,uid,count,length,cid,year,month) values(" +
						"'"+logs.get(i).getUrl().toString()+"'," +
								uid + "," +
								logs.get(i).getCount().toString()+"," +
								logs.get(i).getLength().toString()+"," +
								cid+",YEAR(CURDATE()),MONTH(CURDATE()))");
		}

	}
	
	
	protected HashMap<String, Protocol> makeProtocols(String format, List<UrlLogProtocol> logs)
	{
		HashMap<String, Protocol> protocols = new HashMap<String, Protocol>();
		for(int i=0;i<logs.size();++i)
		{
			String data= format;
			data = data.replace("{DATE}",logs.get(i).getDate().toString());
			data = data.replace("{URL}",logs.get(i).getUrl().toString());
			data = data.replace("{COUNT}",logs.get(i).getCount().toString());
			data = data.replace("{LENGTH}",logs.get(i).getLength().toString());
			data = data.replace("{IP}",logs.get(i).getIp().toString());
			data = data.replace("{CONTENT-TYPE}",logs.get(i).getContent_type().toString());
			if(!protocols.containsKey(logs.get(i).getUnique_id()))
			{
				Protocol row = new Protocol(0,
						logs.get(i).getUnique_id().toString(),
						data,
					((Long)logs.get(i).getLength()));
				protocols.put(logs.get(i).getUnique_id().toString(), row);
			}
			else
				protocols.get(logs.get(i).getUnique_id().toString()).appendData(data);
		}
		return protocols;
	}
	
	
	@SuppressWarnings("unchecked")
	protected void UpdateProtocols(HashMap<String, Protocol> protocols, AbstractDAO dao)
	{
		for(String key : protocols.keySet())
		{
			if(dao.getCountByQuery("select count(*) as count from `protocols` where unique_id = '"+protocols.get(key).getUnique_id()+"'", "count")>0)
				dao.execSql(String.format("update `protocols` set data=CONCAT(data,'%s'), length=length+%d", 
						protocols.get(key).getData(),
						protocols.get(key).getLength()));			
			else
				dao.execSql(String.format("insert into `protocols`(unique_id,data,length) values" +
					" ('%s','%s', %d)", 
					protocols.get(key).getUnique_id(),
					protocols.get(key).getData(),
					protocols.get(key).getLength()));
		}		
	}
	
	
	@SuppressWarnings("unchecked")
	protected void UpdateCtryPopularity(List<UrlLogProtocol> logs, AbstractDAO dao, HashMap<String, Integer> userUids)
	{
		for(int i=0;i<logs.size();++i)
		{
			long ip = 0;
			try{
				ip = StringUtils.ip2value(logs.get(i).getIp().toString());
				logger.debug("ip parsed successfully: " +logs.get(i).getIp().toString()+"->"+ip);
			}
			catch(NumberFormatException e)
			{
				logger.error("IP is not valid for " + logs.get(i).getUnique_id()+": "+logs.get(i).getIp());
			}
			int uid = userUids.get(logs.get(i).getUser().toString());
			String ctry = (String)dao.getSingleValueByQuery("select ctry from `ip2country` where " +
					"sip<"+ip+" and eip>"+ip+" limit 1", "ctry");
			if(dao.getCountByQuery("select count(*) as count from `ctry_popularity` where " +
					"ctry = '"+ctry+"' and " +
					"uid = "+uid+" " +
					"and `year`=YEAR(CURDATE()) and `month`=MONTH(CURDATE())", "count")>0)
				dao.execSql("update `ctry_popularity` set count = count + "+logs.get(i).getCount().toString()+", " +
						"length = length + "+logs.get(i).getLength().toString()+" " +
						"where ctry = '"+ctry+"' and uid = "+uid+" and month=MONTH(CURDATE()) and year=YEAR(CURDATE())");
			else
				dao.execSql("insert into `ctry_popularity`(ctry,count,length,uid,year,month) " +
						"values('"+ctry+"'," +
						""+logs.get(i).getCount().toString()+"," +
						""+logs.get(i).getLength().toString()+"," +
						""+uid+",YEAR(CURDATE()),MONTH(CURDATE()))");
		}		
	}
	
	protected void Aggregate()
	{
		UrlLogProtocolDAO urllogDAO = new UrlLogProtocolDAO();	
		List<UrlLogProtocol> logs = null;
		HashMap<String, Integer> userUids = null;
		logs = urllogDAO.getItemsByQuery("select unique_id,url,SUM(length) as length, date,COUNT(*) as 'count',user,ip,content_type from url_log group by unique_id,url,content_type order by unique_id,date,url,length");
		String format = Configurator.getInstance().getProperty("urllog_format");
		
		userUids = DefineUsersUids(logs, urllogDAO);
		UpdateProtocols(makeProtocols(format, logs), urllogDAO);
		UpdatePopularity(logs, urllogDAO, userUids);
		UpdateCtryPopularity(logs,urllogDAO,userUids);
		
		synchronized (Collector.getWLock()) {
			urllogDAO.execSql("delete from `url_log`");	
		}			
	}
	
	@Override
	protected void daemonize() {
		logger.info("aggregate info");
		Aggregate();
		logger.info("aggregation completed");
	}
}
