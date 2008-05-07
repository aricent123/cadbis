package cadbis.db;

import java.util.List;

import cadbis.bl.UrlCategoryMatch;

public class UrlCategoryMatchDAO extends AbstractDAO<UrlCategoryMatch> {

	public UrlCategoryMatchDAO()
	{
		super(DBConnection.getInstance(), "url_categories");
	}
	
	
	public List<UrlCategoryMatch> getUrlCategoryMatches()
	{
		return this.getItemsByQuery("select * from `url_categories_match`");
	}	
}
