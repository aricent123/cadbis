package cadbis.db;

import cadbis.bl.UrlCategoryDenied;

public class UrlCategoryDeniedDAO extends AbstractDAO<UrlCategoryDenied> {

	public UrlCategoryDeniedDAO()
	{
		super(DBConnection.getInstance(), "url_categories_denied");
	}
}
