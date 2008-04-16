package cadbis.proxy.db;

public class User extends AbstractDbObject<User,Integer> {
	private String user;
	private String password;
	
	public User()
	{
		super(DataAccess.getInstance(), "users");
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
	
	
}
