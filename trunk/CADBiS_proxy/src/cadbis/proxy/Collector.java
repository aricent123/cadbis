package cadbis.proxy;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.proxy.bl.Action;
import cadbis.proxy.bl.CollectedData;
import cadbis.proxy.db.ActionDAO;

public class Collector {
	private List<Action> actions = null;
	private ActionDAO actionDAO = null;
	private static Collector instance = null;
	private final Logger logger = LoggerFactory.getLogger(getClass());
	private HashMap<Integer, Action> actionsOfIps;
	private static Object wLock = null;
	
	
	private Collector(){
		actionDAO = new ActionDAO();
		actionsOfIps = new HashMap<Integer, Action>();
		actions = new ArrayList<Action>();
		wLock = new Object();
	}

	public static Collector getInstance(){
		if(instance == null)
			instance = new Collector();
		return instance;
	}
	
	public Action getActionByIp(String ip)
	{
		return actionsOfIps.get(ip.hashCode());
	}
	
	public List<Action> getActiveSessions()
	{		
		return actionDAO.getItemsByQuery("select * from actions where terminate_cause='Online'");
	}	
	
	public void RefreshInfo()
	{
		actions = getActiveSessions();
		actionsOfIps.clear();
		for(int i=0;i<actions.size();++i)
			actionsOfIps.put(actions.get(i).getIp().toString().hashCode(), actions.get(i));
	}
	
	public void Collect(String ip, String url, Long bytes, Date date)
	{
		synchronized (wLock) {
			Action action = getActionByIp(ip);
			if(action!=null){
				action.getCollectedUrls().add(new CollectedData(url,bytes,date));
				//logger.info("Collecting data... ip="+ip+", url="+url+", bytes="+bytes);
			}
		}
	}
	
	public void FlushCollected()
	{
		synchronized (wLock) {		
			for(int i=0;i<actions.size();++i)
			{
				List<CollectedData> col = actions.get(i).getCollectedUrls();
				for(int j=0; j<col.size(); ++j)
				{
					logger.info("Collected data for ip="+actions.get(i).getIp()+"("+actions.get(i).getUser()+"), url="+col.get(j).url+", bytes="+col.get(j).bytes);
					actionDAO.execSql("insert into url_log values ('"+
							actions.get(i).getUnique_id() +"','"+
							actions.get(i).getUser() +"','"+
							col.get(j).url+"',"+
							col.get(j).bytes+",'"+
							new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(col.get(j).date)+"')");
				}
			}
			actions.clear();
		}
	}
	

	
	
}
