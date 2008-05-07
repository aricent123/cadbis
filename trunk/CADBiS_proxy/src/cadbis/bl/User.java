package cadbis.bl;

public class User implements BusinessObject{
	private String user;
	private String password;
	private Integer uid;
	private Integer gid;
	private String nick;
	private String fio;
	private Integer gender;
	private Integer add_uid;
	private String add_date;
	private Integer blocked;
	private Integer simultaneous_use = 0;
	private Long max_total_traffic;
	private Long max_month_traffic;
	private Long max_week_traffic;
	private Long max_day_traffic;
	
	private Long ttime;
	private Long mtime;
	private Long wtime;
	private Long dtime;
	private Long ttraffic;
	private Long mtraffic;
	private Long wtraffic;
	private Long dtraffic;

	
	public String[][] getPerstistenceFields() {
		String[][] fields = {
							{"user","String"},
							{"password","String"},
							{"uid","Integer"},
							{"gid","Integer"}, 
							{"nick","String"},
							{"fio","String"},
							{"gender","Integer"}, 
							{"add_uid","Integer"}, 
							{"add_date","String"},
							{"blocked","Integer"},
							{"simultaneous_use",	"Integer"}, 
							{"max_total_traffic",	"Long"},
							{"max_month_traffic",	"Long"},
							{"max_week_traffic",	"Long"},
							{"max_day_traffic",	"Long"},
						};
		return fields;
	}	
	
	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	public String getUser() {
		return user;
	}

	public void setUser(String user) {
		this.user = user;
	}

	public Integer getUid() {
		return uid;
	}

	public void setUid(Integer uid) {
		this.uid = uid;
	}

	public Integer getGid() {
		return gid;
	}

	public void setGid(Integer gid) {
		this.gid = gid;
	}

	public String getNick() {
		return nick;
	}

	public void setNick(String nick) {
		this.nick = nick;
	}

	public String getFio() {
		return fio;
	}

	public void setFio(String fio) {
		this.fio = fio;
	}

	public Integer getGender() {
		return gender;
	}

	public void setGender(Integer gender) {
		this.gender = gender;
	}

	public Integer getAdd_uid() {
		return add_uid;
	}

	public void setAdd_uid(Integer add_uid) {
		this.add_uid = add_uid;
	}

	public String getAdd_date() {
		return add_date;
	}

	public void setAdd_date(String add_date) {
		this.add_date = add_date;
	}

	public Integer getBlocked() {
		return blocked;
	}

	public void setBlocked(Integer blocked) {
		this.blocked = blocked;
	}


	public Long getTtime() {
		return ttime;
	}

	public void setTtime(Long ttime) {
		this.ttime = ttime;
	}

	public Long getMtime() {
		return mtime;
	}

	public void setMtime(Long mtime) {
		this.mtime = mtime;
	}

	public Long getWtime() {
		return wtime;
	}

	public void setWtime(Long wtime) {
		this.wtime = wtime;
	}

	public Long getDtime() {
		return dtime;
	}

	public void setDtime(Long dtime) {
		this.dtime = dtime;
	}

	public Long getTtraffic() {
		return ttraffic;
	}

	public void setTtraffic(Long ttraffic) {
		this.ttraffic = ttraffic;
	}

	public Long getMtraffic() {
		return mtraffic;
	}

	public void setMtraffic(Long mtraffic) {
		this.mtraffic = mtraffic;
	}

	public Long getWtraffic() {
		return wtraffic;
	}

	public void setWtraffic(Long wtraffic) {
		this.wtraffic = wtraffic;
	}

	public Long getDtraffic() {
		return dtraffic;
	}

	public void setDtraffic(Long dtraffic) {
		this.dtraffic = dtraffic;
	}
	
	
	public void setDstats(UserStats dStats){
		this.dtraffic = dStats.traffic;
		this.dtime = dStats.time_on;
	}
	public void setWstats(UserStats wStats){
		this.wtraffic = wStats.traffic;
		this.wtime = wStats.time_on;
	}
	public void setMstats(UserStats mStats){
		this.mtraffic = mStats.traffic;
		this.mtime = mStats.time_on;
	}
	public void setTstats(UserStats tStats){
		this.ttraffic = tStats.traffic;
		this.ttime = tStats.time_on;
	}

	public Integer getSimultaneous_use() {
		return simultaneous_use;
	}

	public void setSimultaneous_use(Integer simultaneous_use) {
		this.simultaneous_use = simultaneous_use;
	}

	public Long getMax_total_traffic() {
		return max_total_traffic;
	}

	public void setMax_total_traffic(Long max_total_traffic) {
		this.max_total_traffic = max_total_traffic;
	}

	public Long getMax_month_traffic() {
		return max_month_traffic;
	}

	public void setMax_month_traffic(Long max_month_traffic) {
		this.max_month_traffic = max_month_traffic;
	}

	public Long getMax_week_traffic() {
		return max_week_traffic;
	}

	public void setMax_week_traffic(Long max_week_traffic) {
		this.max_week_traffic = max_week_traffic;
	}

	public Long getMax_day_traffic() {
		return max_day_traffic;
	}

	public void setMax_day_traffic(Long max_day_traffic) {
		this.max_day_traffic = max_day_traffic;
	}
}
