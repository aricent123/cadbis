package cadbis.proxy.db;

import cadbis.proxy.bl.Action;

public class ActionDAO extends AbstractDbObject<Action> {

	public ActionDAO()
	{
		super(DataAccess.getInstance(), "actions");
	}
	
}
