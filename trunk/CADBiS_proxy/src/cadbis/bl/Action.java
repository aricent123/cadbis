package cadbis.bl;

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

	public void setUser(String user) {
		this.user = user;
	}

	public Object getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}

	public Integer getGid() {
		return gid;
	}

	public void setGid(Integer gid) {
		this.gid = gid;
	}

	public Object getUnique_id() {
		return unique_id;
	}

	public void setUnique_id(String unique_id) {
		this.unique_id = unique_id;
	}

	public Object getTime_on() {
		return time_on;
	}

	public void setTime_on(Integer time_on) {
		this.time_on = time_on;
	}

	public String getStart_time() {
		return start_time;
	}

	public void setStart_time(String start_time) {
		this.start_time = start_time;
	}

	public String getStop_time() {
		return stop_time;
	}

	public void setStop_time(String stop_time) {
		this.stop_time = stop_time;
	}

	public Object getIn_bytes() {
		return in_bytes;
	}

	public void setIn_bytes(Integer in_bytes) {
		this.in_bytes = in_bytes;
	}

	public Object getOut_bytes() {
		return out_bytes;
	}

	public void setOut_bytes(Integer out_bytes) {
		this.out_bytes = out_bytes;
	}

	public Object getIp() {
		return ip;
	}

	public void setIp(String ip) {
		this.ip = ip;
	}

	public Object getServer() {
		return server;
	}

	public void setServer(String server) {
		this.server = server;
	}

	public Object getClient_ip() {
		return client_ip;
	}

	public void setClient_ip(String client_ip) {
		this.client_ip = client_ip;
	}

	public Object getPort() {
		return port;
	}

	public void setPort(Integer port) {
		this.port = port;
	}



	public List<UrlDenied> getDeniedUrls() {
		return DeniedUrls;
	}



	public void setDeniedUrls(List<UrlDenied> deniedUrls) {
		DeniedUrls = deniedUrls;
	}	

	
}
