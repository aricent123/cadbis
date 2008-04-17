package cadbis.proxy.db;

import cadbis.proxy.bl.UrlDenied;

public class DeniedUrlDAO extends AbstractDAO<UrlDenied> {

	public DeniedUrlDAO()
	{
		super(DBConnection.getInstance(), "url_denied");
	}
	
}
