package cadbis.proxy;

import java.util.HashMap;
import java.util.List;

import cadbis.CADBiSDaemon;
import cadbis.bl.ContentCategory;
import cadbis.bl.UrlCategoryMatch;
import cadbis.db.UrlCategoryMatchDAO;

public class Categorizer extends CADBiSDaemon{
	protected HashMap<String, Integer> url_cat = null;	
	private static Categorizer instance = null;
	
	protected Categorizer() {
		super("Categorizer",Integer.valueOf(ProxyConfigurator.getInstance().getProperty("categorizer_period")));
		reloadCategories();
	}

	protected void reloadCategories()
	{
		List<UrlCategoryMatch> lst = new UrlCategoryMatchDAO().getUrlCategoryMatches();
		for(UrlCategoryMatch match : lst)
			if(!url_cat.containsKey(match.getUrl()))
				url_cat.put(match.getUrl(), match.getCid());
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
	
	
	@Override
	protected void daemonize() {
		logger.info("Waking up, reloading categories...");
		reloadCategories();
	}
}