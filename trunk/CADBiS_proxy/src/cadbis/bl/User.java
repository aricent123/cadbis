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
	private Integer activated;
	private String last_connection;
	private Integer simultaneous_use;
	
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
							{"activated","Integer"},
							{"last_connection","String"}, 
							{"simultaneous_use",	"Integer"}, 
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

	public Integer getActivated() {
		return activated;
	}

	public void setActivated(Integer activated) {
		this.activated = activated;
	}

	public String getLast_connection() {
		return last_connection;
	}

	public void setLast_connection(String last_connection) {
		this.last_connection = last_connection;
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

}
