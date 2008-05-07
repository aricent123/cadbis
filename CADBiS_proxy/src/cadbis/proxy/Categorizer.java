package cadbis.proxy;

import java.util.HashMap;
import java.util.List;

import cadbis.CADBiSDaemon;
import cadbis.bl.ContentCategory;
import cadbis.bl.UrlCategoryMatch;
import cadbis.db.ContentCategoryDAO;
import cadbis.db.UrlCategoryMatchDAO;

public class Categorizer extends CADBiSDaemon{
	protected HashMap<String, Integer> url_cat = null;
	protected List<ContentCategory> cats = null;
	protected List<String> uswords = null;
	private static Categorizer instance = null;
	protected static Object rwLock = new Object(); 
	
	protected Categorizer() {
		super("Categorizer",Integer.valueOf(ProxyConfigurator.getInstance().getProperty("categorizer_period")));
		reloadData();
	}

	protected void reloadData()
	{
		synchronized (rwLock) {
			url_cat = new HashMap<String, Integer>();
			List<UrlCategoryMatch> lst = new UrlCategoryMatchDAO().getUrlCategoryMatches();
			for(UrlCategoryMatch match : lst)
				if(!url_cat.containsKey(match.getUrl()))
					url_cat.put(match.getUrl(), match.getCid());
			
			cats  = new ContentCategoryDAO().getCategoriesWithWords();
			uswords = new ContentCategoryDAO().getUnsenseWords();		
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
}