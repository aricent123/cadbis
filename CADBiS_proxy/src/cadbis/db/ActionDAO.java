package cadbis.db;

import cadbis.bl.Action;

public class ActionDAO extends AbstractDAO<Action> {

	public ActionDAO()
	{
		super(DBConnection.getInstance(), "actions");
	}
	
}
