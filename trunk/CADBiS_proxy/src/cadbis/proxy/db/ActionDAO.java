package cadbis.proxy.db;

import cadbis.proxy.bl.Action;

public class ActionDAO extends AbstractDAO<Action> {

	public ActionDAO()
	{
		super(DBConnection.getInstance(), "actions");
	}
	
}
