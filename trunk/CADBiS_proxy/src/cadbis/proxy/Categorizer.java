package cadbis.proxy;

import java.io.UnsupportedEncodingException;
import java.nio.charset.CharacterCodingException;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;
import java.util.List;
import java.util.Set;

import cadbis.CADBiSDaemon;
import cadbis.bl.ContentAnalyzeResult;
import cadbis.bl.ContentCategory;
import cadbis.bl.UrlCategoryDenied;
import cadbis.bl.UrlCategoryMatch;
import cadbis.db.UrlCategoryDeniedDAO;
import cadbis.db.ContentCategoryDAO;
import cadbis.db.UrlCategoryMatchDAO;
import cadbis.proxy.httpparser.ContentAnalyzer;

public class Categorizer extends CADBiSDaemon{
	protected HashMap<String, Integer> url_cat = null;
	protected List<ContentCategory> cats = null;
	protected List<String> uswords = null;
	protected HashMap<String, Integer> kwds_weights = null;
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
			ContentCategoryDAO dao = new ContentCategoryDAO();
			cats  = dao.getCategoriesWithWords();
			uswords = dao.getUnsenseWords();
			kwds_weights = dao.getKeywordsWeights();
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
	
	/**
	 * Content category recognition
	 * @param content - Content of URL
	 * @param categories - List of categories 
	 * @param unsenseWords - List of unsensable words (should exclude them)
	 * @param charset - Encoding of content (e.g. UTF-8, cp1251, etc)
	 * @return recognizedCategory
	 */
	protected ContentCategory recognizeCategory(String url,String content,List<ContentCategory> categories, List<String> unsenseWords, String charset){
		ContentAnalyzeResult res = new ContentAnalyzeResult();
	    // analyze the content
		try{			
			res = ContentAnalyzer.Analyze(content,cats, uswords,kwds_weights, charset);
		}
		catch(CharacterCodingException e)
		{
			logger.error("Error converting content to utf: " + e.getMessage());
		}
		catch(UnsupportedEncodingException e)
		{
			logger.error("Error converting content to utf: unsupported charset: " + e.getMessage());
		}
			
	    HashMap<Integer, ContentCategory> cats_by_cid = new HashMap<Integer, ContentCategory>();
	    for(ContentCategory cat : cats)
	    	cats_by_cid.put(cat.getCid(),cat);
	    
	    // find the most suitable category
		Iterator<Integer> iter_max = res.cats_coefs.keySet().iterator();
		Integer cidMax = 0;
	    while (iter_max.hasNext()) {
	    	Integer cid = iter_max.next();
	    	ContentCategory cat = cats_by_cid.get(cid);
	    	logger.debug("'"+cat.getTitle()+"'="+res.cats_coefs.get(cid));
	    	if(res.cats_coefs.get(cid) > res.cats_coefs.get(cidMax))
	    		cidMax = cid;
	    }
	    
	    ContentCategoryDAO dao = new ContentCategoryDAO();
		Iterator<String> iter = res.keywords.keySet().iterator();
	    while (iter.hasNext()) 
	    {
	    	String keyword = iter.next();
	    	Integer confCid = 0;
	    	for(int i=0;i<cats.size();++i)
	    	{
	    		if(cats.get(i).getKeywords().contains(keyword))
	    			confCid = cats.get(i).getCid();
	    	}
	    	
	    	if(confCid != cidMax){
		    	if(confCid!=0) // conflict
		    		dao.addUrlCategoryConflict(keyword, cidMax, confCid, url);
		    	else if(cidMax != 0)// no conflicts
		    		dao.attachUrlCategoryKeyword(cidMax, keyword);
	    	}
	    }
	    
	    logger.info("Found cid with max rang: '"+cats_by_cid.get(cidMax).getTitle()+"'="+res.cats_coefs.get(cidMax));
		return cats_by_cid.get(cidMax);
	}
	
	public synchronized Integer recognizeAndAddCategory(String url, String content, String charset)
	{
		ContentCategory cat = recognizeCategory(url, content, cats, uswords,charset);
		logger.info("Recognizing and adding a category for url='"+url+"' = " + cat.getTitle());
		if(!url_cat.containsKey(url)){
			url_cat.put(url, cat.getCid());
			new ContentCategoryDAO().addUrlCategoryMatch(url, cat.getCid());
		}
		return cat.getCid();
	}
	
	
	public Integer getCategoryForUrl(String url)
	{
		logger.debug("Trying to recognize category for " + url);
		if(!url_cat.containsKey(url))
		{
			logger.debug("Category unrecognized, reloading data... ");
			reloadData();
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