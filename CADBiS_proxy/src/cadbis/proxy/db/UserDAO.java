package cadbis.proxy.db;

import cadbis.proxy.bl.User;

public class UserDAO extends AbstractDbObject<User> {

	public UserDAO()
	{
		super(DataAccess.getInstance(), "users");
	}
	
}
