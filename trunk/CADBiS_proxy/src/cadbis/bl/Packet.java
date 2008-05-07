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
	private Integer port_limit;
	private Integer rang;
	private Integer exceed_times;
	private Integer users_count = 0;
	private Integer simuluse_sum  = 0;	
	
	public String[][] getPerstistenceFields() {
		String[][] fields = {
					{"gid",					"Integer"},
					{"packet",				"String"},
					{"blocked",				"Integer"},
					{"total_time_limit",	"Long"},
					{"month_time_limit",	"Long"},
					{"week_time_limit",		"Long"},
					{"day_time_limit",		"Long"},
					{"total_traffic_limit",	"Long"}, 
					{"month_traffic_limit",	"Long"},
					{"week_traffic_limit",	"Long"},
					{"day_traffic_limit",	"Long"},
					{"login_time",			"String"},					
					{"port_limit",			"Integer"},
					{"users_count",			"Integer"},
					{"rang",				"Integer"},
					{"exceed_times",		"Integer"},
					{"simuluse_sum",		"Integer"}
					};
		return fields;
	}



	public Integer getGid() {
		return gid;
	}



	public void setGid(Integer gid) {
		this.gid = gid;
	}



	public String getPacket() {
		return packet;
	}



	public void setPacket(String packet) {
		this.packet = packet;
	}



	public Integer getBlocked() {
		return blocked;
	}



	public void setBlocked(Integer blocked) {
		this.blocked = blocked;
	}



	public Long getTotal_time_limit() {
		return total_time_limit;
	}



	public void setTotal_time_limit(Object total_time_limit) {
		this.total_time_limit = (Long)total_time_limit;
	}



	public Long getMonth_time_limit() {
		return month_time_limit;
	}



	public void setMonth_time_limit(Long month_time_limit) {
		this.month_time_limit = month_time_limit;
	}



	public Long getWeek_time_limit() {
		return week_time_limit;
	}



	public void setWeek_time_limit(Long week_time_limit) {
		this.week_time_limit = week_time_limit;
	}



	public Long getDay_time_limit() {
		return day_time_limit;
	}



	public void setDay_time_limit(Long day_time_limit) {
		this.day_time_limit = day_time_limit;
	}


	public Long getTotal_traffic_limit() {
		return total_traffic_limit;
	}

	public void setTotal_traffic_limit(Long total_traffic_limit) {
		this.total_traffic_limit = total_traffic_limit;
	}

	public Long getMonth_traffic_limit() {
		return month_traffic_limit;
	}

	public void setMonth_traffic_limit(Long month_traffic_limit) {
		this.month_traffic_limit = month_traffic_limit;
	}

	public Long getWeek_traffic_limit() {
		return week_traffic_limit;
	}

	public void setWeek_traffic_limit(Long week_traffic_limit) {
		this.week_traffic_limit = week_traffic_limit;
	}

	public Long getDay_traffic_limit() {
		return day_traffic_limit;
	}

	public void setDay_traffic_limit(Long day_traffic_limit) {
		this.day_traffic_limit = day_traffic_limit;
	}

	public String getLogin_time() {
		return login_time;
	}

	public void setLogin_time(String login_time) {
		this.login_time = login_time;
	}

	public void setTotal_time_limit(Long total_time_limit) {
		this.total_time_limit = total_time_limit;
	}	

	public Integer getPort_limit() {
		return port_limit;
	}

	public void setPort_limit(Integer port_limit) {
		this.port_limit = port_limit;
	}
	
	public Integer getUsers_count() {
		return users_count;
	}

	public void setUsers_count(Integer users_count) {
		this.users_count = users_count;
	}
	
	public Integer getRang() {
		return rang;
	}

	public void setRang(Integer rang) {
		this.rang = rang;
	}

	public Integer getExceed_times() {
		return exceed_times;
	}

	public void setExceed_times(Integer exceed_times) {
		this.exceed_times = exceed_times;
	}	
	
	public Integer getSimuluse_sum() {
		return simuluse_sum;
	}

	public void setSimuluse_sum(Integer simuluse_sum) {
		this.simuluse_sum = simuluse_sum;
	}	
	
	
	public Integer getSummarRang() {
		return rang*simuluse_sum;
	}	
	


}
