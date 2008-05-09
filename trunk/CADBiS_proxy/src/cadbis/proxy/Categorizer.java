package cadbis.proxy;

import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

import cadbis.CADBiSDaemon;
import cadbis.bl.ContentCategory;
import cadbis.bl.UrlCategoryDenied;
import cadbis.bl.UrlCategoryMatch;
import cadbis.db.UrlCategoryDeniedDAO;
import cadbis.db.ContentCategoryDAO;
import cadbis.db.UrlCategoryMatchDAO;
import cadbis.utils.StringUtils;

public class Categorizer extends CADBiSDaemon{
	protected HashMap<String, Integer> url_cat = null;
	protected List<ContentCategory> cats = null;
	protected List<String> uswords = null;
	protected Set<String> catsAccessDenied = null; 
	private static Categorizer instance = null;
	protected static Object rwLock = new Object(); 
	
	protected Categorizer() {
		super("Categorizer",Integer.valueOf(ProxyConfigurator.getInstance().getProperty("categorizer_period")));
		reloadData();
	}

	protected synchronized void reloadData()
	{
			url_cat = new HashMap<String, Integer>();
			List<UrlCategoryMatch> lst = new UrlCategoryMatchDAO().getUrlCategoryMatches();
			for(UrlCategoryMatch match : lst)
				if(!url_cat.containsKey(match.getUrl()))
					url_cat.put(match.getUrl(), match.getCid());
			
			cats  = new ContentCategoryDAO().getCategoriesWithWords();
			uswords = new ContentCategoryDAO().getUnsenseWords();
			
			catsAccessDenied = new HashSet<String>();
			List<UrlCategoryDenied> catDenied = 
				new UrlCategoryDeniedDAO().getItemsByQuery("select * from url_categories_denied");
			for(UrlCategoryDenied url : catDenied)
			{
				if(!catsAccessDenied.contains(url.getCid()+"/"+url.getGid()))
					catsAccessDenied.add(url.getCid()+"/"+url.getGid());
			}
	}
	
	public static Categorizer getInstance(){
		if(instance == null)
			instance = new Categorizer();
		return (Categorizer)instance;
	}
	
	protected ContentCategory recognizeCategory(String content,List<ContentCategory> categories, List<String> unsenseWords){		
		ContentCategory res = new ContentCategory();
		res.setCid(0);
		res.setTitle("Other");
		return res;
	}
	
	public Integer recognizeAndAddCategory(String url, String content)
	{
		ContentCategory cat = recognizeCategory(content, cats, uswords);
		logger.info("Content recognizing: '"+StringUtils.KillTags(content)+"', size="+content.length());
		logger.info("Recognizing and adding a category for url='"+url+"' = " + cat.getTitle());
		new ContentCategoryDAO().execSql(String.format("insert into url_categories_match(url,cid) values('%s',%d)",url,cat.getCid()));
		url_cat.put(url, cat.getCid());
		return cat.getCid();
	}
	
	
	public Integer getCategoryForUrl(String url)
	{
		logger.debug("Trying to recognize category for " + url);
		if(!url_cat.containsKey(url))
		{
			logger.debug("Category unrecognized, reloading data... ");
			//	reloadData();
		}
		else
			logger.debug("Category recognized, = " + url_cat.get(url));
		return url_cat.get(url);
	}
	
	@Override
	protected void daemonize() {
		logger.info("Waking up, reloading categories...");
		reloadData();
	}
	
	public boolean checkAccessToCategory(Integer gid, Integer cid)
	{
		return !catsAccessDenied.contains(cid+"/"+gid);
	}	
}