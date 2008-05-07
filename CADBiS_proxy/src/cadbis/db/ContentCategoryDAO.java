package cadbis.db;

import java.util.List;

import cadbis.bl.ContentCategory;

public class ContentCategoryDAO extends AbstractDAO<ContentCategory> {

	public ContentCategoryDAO()
	{
		super(DBConnection.getInstance(), "url_categories");
	}
	
	public List<ContentCategory> getCategoriesWithWords()
	{
		List<ContentCategory> lst = this.getItemsByQuery("select * from `url_categories`");
		for(ContentCategory cat : lst)
			cat.setKeywords(getKeywords(cat.getCid()));
		return lst;
	}
	
	public List<String> getKeywords(Integer cid)
	{
		return this.getListOfStringsByQuery(String.format("select keyword from `url_categories_keywords` where cid=%d",cid), "keyword");
	}
	
	public List<String> getUnsenseWords()
	{
		return this.getListOfStringsByQuery("select keyword from `url_categories_unsensewords`", "keyword");
	}	
}
