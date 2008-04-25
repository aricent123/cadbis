package cadbis.db;

import cadbis.bl.UrlLog;

public class UrlLogDAO extends AbstractDAO<UrlLog> {

	public UrlLogDAO()
	{
		super(DBConnection.getInstance(), "url_log");
	}
	
}
