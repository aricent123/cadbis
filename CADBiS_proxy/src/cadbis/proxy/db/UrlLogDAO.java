package cadbis.proxy.db;

import cadbis.proxy.bl.UrlLog;

public class UrlLogDAO extends AbstractDAO<UrlLog> {

	public UrlLogDAO()
	{
		super(DBConnection.getInstance(), "url_log");
	}
	
}
