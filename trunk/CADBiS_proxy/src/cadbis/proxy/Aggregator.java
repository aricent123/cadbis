package cadbis.proxy;

import java.util.HashMap;
import java.util.List;

import cadbis.CADBiSDaemon;
import cadbis.bl.Protocol;
import cadbis.bl.UrlLogProtocol;
import cadbis.db.AbstractDAO;
import cadbis.db.ProtocolDAO;
import cadbis.db.UrlLogProtocolDAO;
import cadbis.utils.StringUtils;

public class Aggregator extends CADBiSDaemon {
	private static Aggregator instance = null;
		
	protected Aggregator() {
		super("Aggregator",Integer.valueOf(ProxyConfigurator.getInstance().getProperty("aggregator_period")));
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
	
	protected void parseAppendProtocolLog(String data, String unique_id, String sep1,String sep2, HashMap<Integer, UrlLogProtocol> proto_logs){
		String[] cproto_items = data.split(StringUtils.escapeRE(sep1));
		for(String cproto_item : cproto_items){
			String[] cif = cproto_item.split(StringUtils.escapeRE(sep2));
			String ctype = (cif.length > 5)?cif[5]:"";
			Integer cif_key = new String(cif[1]+ctype).hashCode();
			long cif_len = Long.valueOf(cif[3]);
			int cif_count = Integer.parseInt(cif[2]);			
			if(proto_logs.containsKey(cif_key))
			{
				proto_logs.get(cif_key).setDate(cif[0]);
				proto_logs.get(cif_key).addCount(cif_count);
				proto_logs.get(cif_key).addLength(cif_len);
			}
			else
			{
				proto_logs.put(cif_key, new UrlLogProtocol(unique_id,cif[1],cif_len,cif[0],cif_count,"",cif[4],ctype));
			}
		}
	}
	
	
	protected void appendProtocolItem(HashMap<String, Protocol> protocols, String sep1, String sep2, 
			String unique_id, String date, String url, int count, long length, 
			String ip, String content_type, boolean appendLength)
	{
		String data = "";
		data += date 			+	sep2;
		data += url				+	sep2;
		data += count			+	sep2;
		data += length			+	sep2;
		data += ip				+	sep2;
		data += content_type	+	sep1;		
		if(!protocols.containsKey(unique_id))
		{
			Protocol row = new Protocol(0,
					unique_id,
					data,
				((Long)length));
			protocols.put(unique_id, row);
		}
		else
			{
				protocols.get(unique_id).appendData(data);
				if(appendLength)				
					protocols.get(unique_id).appendLength(Long.valueOf(length));
			}
	}
	
	protected HashMap<String, Protocol> makeProtocols(String sep1, String sep2, List<UrlLogProtocol> logs)
	{
		HashMap<String, Protocol> protocols = new HashMap<String, Protocol>();		
		try
		{
			for(int i=0;i<logs.size();++i)
				appendProtocolItem(protocols,sep1,sep2,
						logs.get(i).getUnique_id().toString(),
						logs.get(i).getDate().toString(),
						logs.get(i).getUrl().toString(),
						Integer.valueOf((Integer)logs.get(i).getCount()),
						Long.valueOf((Long)logs.get(i).getLength()),
						logs.get(i).getIp().toString(),
						logs.get(i).getContent_type().toString(), false);
			
			
			if(ProxyConfigurator.getInstance().getProperty("aggregate_protocols").equals("enabled"))
			{
				HashMap<Integer, UrlLogProtocol> proto_logs = new HashMap<Integer, UrlLogProtocol>();
				ProtocolDAO dao = new ProtocolDAO();
				for(String key : protocols.keySet())
				{
					String unique_id = protocols.get(key).getUnique_id().toString();
					List<Protocol> eproto = dao.getItemsByQuery(String.format("select * from `protocols` where " +
							"unique_id='%s'",protocols.get(key).getUnique_id()));
					
					parseAppendProtocolLog(protocols.get(key).getData().toString(),unique_id,sep1,sep2,proto_logs);
					for(Protocol ep : eproto)
						parseAppendProtocolLog(ep.getData().toString().toString(),unique_id,sep1,sep2,proto_logs);
				}
				protocols.clear();
				for(Integer key : proto_logs.keySet())
					appendProtocolItem(protocols,sep1,sep2,
							proto_logs.get(key).getUnique_id().toString(),
							proto_logs.get(key).getDate().toString(),
							proto_logs.get(key).getUrl().toString(),
							Integer.valueOf((Integer)proto_logs.get(key).getCount()),
							Long.valueOf((Long)proto_logs.get(key).getLength()),
							proto_logs.get(key).getIp().toString(),
							proto_logs.get(key).getContent_type().toString(), true);
				
				for(String key : protocols.keySet())
					dao.execSql(String.format("update `protocols` set data='' where " +
						"unique_id='%s'",protocols.get(key).getUnique_id()));				
			}
		}
		catch(Exception e)
		{
			logger.error("Parsing the protocols error:" + e.getMessage());
		}
		
		return protocols;
	}
	
	
	@SuppressWarnings("unchecked")
	protected void UpdateProtocols(HashMap<String, Protocol> protocols, AbstractDAO dao)
	{
		for(String key : protocols.keySet())
		{
			if(dao.getCountByQuery("select count(*) as count from `protocols` where unique_id = '"+protocols.get(key).getUnique_id()+"'", "count")>0)
				dao.execSql(String.format("update `protocols` set data=CONCAT(data,'%s'), length=length+%d where unique_id='%s'", 
						protocols.get(key).getData(),
						protocols.get(key).getLength(),
						protocols.get(key).getUnique_id()));			
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
					"sip<"+ip+" and eip>"+ip+"", "ctry");
			if(ctry!=null)
			{
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
	}
	
	protected void Aggregate()
	{
		synchronized (Collector.getWLock()) {			
			try
			{
				UrlLogProtocolDAO urllogDAO = new UrlLogProtocolDAO();	
				List<UrlLogProtocol> logs = null;
				HashMap<String, Integer> userUids = null;
				logs = urllogDAO.getItemsByQuery("select unique_id,url,SUM(length) as length, date,COUNT(*) as 'count',user,ip,content_type from url_log group by unique_id,url,content_type order by unique_id,date,url,length");
				if(logs!= null)
				{
					userUids = DefineUsersUids(logs, urllogDAO);
					String sep1 = ProxyConfigurator.getInstance().getProperty("urllog_sep1");
					String sep2 = ProxyConfigurator.getInstance().getProperty("urllog_sep2");				
					UpdateProtocols(makeProtocols(sep1, sep2, logs), urllogDAO);
					UpdatePopularity(logs, urllogDAO, userUids);
					UpdateCtryPopularity(logs,urllogDAO,userUids);			
					urllogDAO.execSql("delete from `url_log`");	
				}
			}
			catch(Exception e)
			{
				logger.error("aggregation error:"+e.getMessage());
			}
		}
	}
	
	@Override
	protected void postdaemonize() {

	}
	
	@Override
	protected void daemonize() {
		logger.info("aggregate info");
		Aggregate();
		logger.info("aggregation completed");			
	}
}
