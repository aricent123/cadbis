package cadbis.proxy.db;

import cadbis.proxy.bl.User;

public class UserDAO extends AbstractDAO<User> {

	public UserDAO()
	{
		super(DBConnection.getInstance(), "users");
	}
	
}
