package cadbis.db;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.HashMap;
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

	public HashMap<String, Integer> getKeywordsWeights()
	{
		String query = "select keyword,weight from `url_categories_keywords`";
		HashMap<String, Integer> res = new HashMap<String, Integer>();
		if(dataAccess==null)
			return null;		
		ResultSet rs = null;
		try
		{
		   rs = getResultSet(query);
		   while(rs!=null && rs.next ())
			   res.put(getString(rs,"keyword"),rs.getInt("weight"));
		}
		catch(SQLException e)
		{
			logger.error("Query '"+query+"' execution error: " + e.getMessage());
		}
		finally
		{
			closeRs(rs);
		}
		return res;		
	}	
	
	public List<String> getUnsenseWords()
	{
		return this.getListOfStringsByQuery("select keyword from `url_categories_unsensewords`", "keyword");
	}
	
	public void addUrlCategoryConflict(String keyword, Integer forCid, Integer inCid, String url)
	{
		keyword = setStringUtf(keyword);
		new ContentCategoryDAO().execSql(String.format("insert into `url_categories_conflicts`(keyword,forcid,incid,date,url) value('%s',%d,%d,NOW(),'%s')",keyword,forCid,inCid,url));
	}
	
	public void attachUrlCategoryKeyword(Integer cid, String keyword)
	{
		keyword = setStringUtf(keyword);
		new ContentCategoryDAO().execSql(String.format("insert into `url_categories_keywords`(cid,keyword) values(%d,'%s')",cid,keyword));
	}
	
	public void updateContentCategory(Integer cid, String title)
	{		
		title = setStringUtf(title);
		new ContentCategoryDAO().execSql(String.format("update `url_categories` set title='%s' where cid=%d",title,cid));
	}
	
	public void addUrlCategoryMatch(String url, Integer cid)
	{
		new ContentCategoryDAO().execSql(String.format("insert into url_categories_match(url,cid) values('%s',%d)",url,cid));
	}
	
}
