package cadbis.db;

import cadbis.bl.UrlDenied;

public class DeniedUrlDAO extends AbstractDAO<UrlDenied> {

	public DeniedUrlDAO()
	{
		super(DBConnection.getInstance(), "url_denied");
	}
	
}
