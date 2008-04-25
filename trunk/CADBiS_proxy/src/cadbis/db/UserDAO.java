package cadbis.db;

import cadbis.bl.User;

public class UserDAO extends AbstractDAO<User> {

	public UserDAO()
	{
		super(DBConnection.getInstance(), "users");
	}
	
}
