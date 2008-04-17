package cadbis.proxy.bl;

public class User implements BusinessObject{
	private String user;
	private String password;
		
	public String[][] getPerstistenceFields() {
		String[][] fields = {
						{"user","String"},
						{"password","String"}
					};
		return fields;
	}	
	
	public String getPassword() {
		return password;
	}

	public void setPassword(Object password) {
		this.password = (String)password;
	}

	public String getUser() {
		return user;
	}

	public void setUser(Object user) {
		this.user = (String)user;
	}
	
}
