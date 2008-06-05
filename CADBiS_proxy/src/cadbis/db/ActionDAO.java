package cadbis.db;

import java.util.List;

import cadbis.bl.Action;

public class ActionDAO extends AbstractDAO<Action> {

	public ActionDAO()
	{
		super(DBConnection.getInstance(), "actions");
	}
	
	
	public List<Action> getOnlineSessions()
	{
		return getItemsByQuery(String.format("select * from `actions` where terminate_cause='Online'"));
	}
}
