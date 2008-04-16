package cadbis.proxy.bl;

import java.sql.Date;

public class Action implements BusinessObject{
	String user;
	String id;
	Integer gid;
	String unique_id;
	Integer time_on;
	Date start_time;
	Date stop_time;
	Integer in_bytes;
	Integer out_bytes;
	String ip;
	String server;
	String client_ip;
	Integer port;
		
	public String[] getPerstistenceFields() {
		String[] fields = {
				"user",
				"id",
				"gid",
				"unique_id",
				"time_on",
				"start_time",
				"stop_time",
				"in_bytes",
				"out_bytes",
				"ip",
				"server",
				"client_ip",
				"port"
		};
		return fields;
	}

	public String getUser() {
		return user;
	}

	public void setUser(String user) {
		this.user = user;
	}

	public String getId() {
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

	public String getUnique_id() {
		return unique_id;
	}

	public void setUnique_id(String unique_id) {
		this.unique_id = unique_id;
	}

	public Integer getTime_on() {
		return time_on;
	}

	public void setTime_on(Integer time_on) {
		this.time_on = time_on;
	}

	public Date getStart_time() {
		return start_time;
	}

	public void setStart_time(Date start_time) {
		this.start_time = start_time;
	}

	public Date getStop_time() {
		return stop_time;
	}

	public void setStop_time(Date stop_time) {
		this.stop_time = stop_time;
	}

	public Integer getIn_bytes() {
		return in_bytes;
	}

	public void setIn_bytes(Integer in_bytes) {
		this.in_bytes = in_bytes;
	}

	public Integer getOut_bytes() {
		return out_bytes;
	}

	public void setOut_bytes(Integer out_bytes) {
		this.out_bytes = out_bytes;
	}

	public String getIp() {
		return ip;
	}

	public void setIp(String ip) {
		this.ip = ip;
	}

	public String getServer() {
		return server;
	}

	public void setServer(String server) {
		this.server = server;
	}

	public String getClient_ip() {
		return client_ip;
	}

	public void setClient_ip(String client_ip) {
		this.client_ip = client_ip;
	}

	public Integer getPort() {
		return port;
	}

	public void setPort(Integer port) {
		this.port = port;
	}	

	
}
