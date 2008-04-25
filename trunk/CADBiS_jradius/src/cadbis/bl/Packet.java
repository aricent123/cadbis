package cadbis.bl;

public class Packet implements BusinessObject{
	private Integer gid;
	private String packet;
	private Integer blocked;
	private Long total_time_limit;
	private Long month_time_limit;
	private Long week_time_limit;
	private Long day_time_limit;
	private Long total_traffic_limit;
	private Long month_traffic_limit;
	private Long week_traffic_limit;
	private Long day_traffic_limit;
	private String login_time;
	private Integer simultaneous_use;
	private Integer port_limit;
	
	
		
	public String[][] getPerstistenceFields() {
		String[][] fields = {
					{"gid","Integer"},
					{"packet","String"},
					{"blocked","Integer"},
					{"total_time_limit","Long"},
					{"month_time_limit","Long"},
					{"week_time_limit","Long"},
					{"day_time_limit","Long"},
					{"total_traffic_limit","Long"}, 
					{"month_traffic_limit","Long"},
					{"week_traffic_limit","Long"},
					{"day_traffic_limit","Long"},
					{"login_time","String"},
					{"simultaneous_use","Integer"}, 
					{"port_limit","Integer"},
					};
		return fields;
	}



	public Object getGid() {
		return gid;
	}



	public void setGid(Object gid) {
		this.gid = (Integer)gid;
	}



	public Object getPacket() {
		return packet;
	}



	public void setPacket(Object packet) {
		this.packet = (String)packet;
	}



	public Object getBlocked() {
		return blocked;
	}



	public void setBlocked(Object blocked) {
		this.blocked = (Integer)blocked;
	}



	public Object getTotal_time_limit() {
		return total_time_limit;
	}



	public void setTotal_time_limit(Object total_time_limit) {
		this.total_time_limit = (Long)total_time_limit;
	}



	public Object getMonth_time_limit() {
		return month_time_limit;
	}



	public void setMonth_time_limit(Object month_time_limit) {
		this.month_time_limit = (Long)month_time_limit;
	}



	public Object getWeek_time_limit() {
		return week_time_limit;
	}



	public void setWeek_time_limit(Object week_time_limit) {
		this.week_time_limit = (Long)week_time_limit;
	}



	public Object getDay_time_limit() {
		return day_time_limit;
	}



	public void setDay_time_limit(Object day_time_limit) {
		this.day_time_limit = (Long)day_time_limit;
	}



	public Object getTotal_traffic_limit() {
		return total_traffic_limit;
	}



	public void setTotal_traffic_limit(Object total_traffic_limit) {
		this.total_traffic_limit = (Long)total_traffic_limit;
	}



	public Object getMonth_traffic_limit() {
		return month_traffic_limit;
	}



	public void setMonth_traffic_limit(Object month_traffic_limit) {
		this.month_traffic_limit = (Long)month_traffic_limit;
	}



	public Object getWeek_traffic_limit() {
		return week_traffic_limit;
	}



	public void setWeek_traffic_limit(Object week_traffic_limit) {
		this.week_traffic_limit = (Long)week_traffic_limit;
	}



	public Object getDay_traffic_limit() {
		return day_traffic_limit;
	}



	public void setDay_traffic_limit(Object day_traffic_limit) {
		this.day_traffic_limit = (Long)day_traffic_limit;
	}



	public Object getLogin_time() {
		return login_time;
	}



	public void setLogin_time(Object login_time) {
		this.login_time = (String)login_time;
	}



	public Object getSimultaneous_use() {
		return simultaneous_use;
	}



	public void setSimultaneous_use(Object simultaneous_use) {
		this.simultaneous_use = (Integer)simultaneous_use;
	}



	public Object getPort_limit() {
		return port_limit;
	}



	public void setPort_limit(Object port_limit) {
		this.port_limit = (Integer)port_limit;
	}	
	

	
}
