package cadbis.proxy.bl;

import java.util.ArrayList;
import java.util.List;

public class Action implements BusinessObject{
	private String user;
	private String id;
	private Integer gid;
	private String unique_id;
	private Integer time_on;
	private String start_time;
	private String stop_time;
	private Integer in_bytes;
	private Integer out_bytes;
	private String ip;
	private String server;
	private String client_ip;
	private Integer port;
	
	private List<UrlDenied> DeniedUrls = new ArrayList<UrlDenied>();
	private List<CollectedData> CollectedUrls = new ArrayList<CollectedData>(); 
	public List<CollectedData> getCollectedUrls()
	{
		return CollectedUrls;
	}
	
	
	
	public String[][] getPerstistenceFields() {
		String[][] fields = {
				{"user",		"String"},
				{"id",			"String"},
				{"gid",			"Integer"},
				{"unique_id",	"String"},
				{"time_on",		"Integer"},
				{"start_time",	"String"},
				{"stop_time",	"String"},
				{"in_bytes",	"Integer"},
				{"out_bytes",	"Integer"},
				{"ip",			"String"},
				{"server",		"String"},
				{"client_ip",	"String"},
				{"port",		"Integer"}
		};
		return fields;
	}

	public String getUser() {
		return user;
	}

	public void setUser(Object user) {
		this.user = (String)user;
	}

	public Object getId() {
		return id;
	}

	public void setId(Object id) {
		this.id = (String)id;
	}

	public Integer getGid() {
		return gid;
	}

	public void setGid(Object gid) {
		this.gid = (Integer)gid;
	}

	public Object getUnique_id() {
		return unique_id;
	}

	public void setUnique_id(Object unique_id) {
		this.unique_id = (String)unique_id;
	}

	public Object getTime_on() {
		return time_on;
	}

	public void setTime_on(Object time_on) {
		this.time_on = (Integer)time_on;
	}

	public String getStart_time() {
		return start_time;
	}

	public void setStart_time(Object start_time) {
		this.start_time = (String)start_time;
	}

	public String getStop_time() {
		return stop_time;
	}

	public void setStop_time(Object stop_time) {
		this.stop_time = (String)stop_time;
	}

	public Object getIn_bytes() {
		return in_bytes;
	}

	public void setIn_bytes(Object in_bytes) {
		this.in_bytes = (Integer)in_bytes;
	}

	public Object getOut_bytes() {
		return out_bytes;
	}

	public void setOut_bytes(Object out_bytes) {
		this.out_bytes = (Integer)out_bytes;
	}

	public Object getIp() {
		return ip;
	}

	public void setIp(Object ip) {
		this.ip = (String)ip;
	}

	public Object getServer() {
		return server;
	}

	public void setServer(Object server) {
		this.server = (String)server;
	}

	public Object getClient_ip() {
		return client_ip;
	}

	public void setClient_ip(Object client_ip) {
		this.client_ip = (String)client_ip;
	}

	public Object getPort() {
		return port;
	}

	public void setPort(Object port) {
		this.port = (Integer)port;
	}



	public List<UrlDenied> getDeniedUrls() {
		return DeniedUrls;
	}



	public void setDeniedUrls(List<UrlDenied> deniedUrls) {
		DeniedUrls = deniedUrls;
	}	

	
}
