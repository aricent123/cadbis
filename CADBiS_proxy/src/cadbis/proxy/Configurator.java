package cadbis.proxy;

import java.io.IOException;
import java.io.InputStream;
import java.util.Properties;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.CADBiSThread;
import cadbis.proxy.db.DBConnection;
import cadbis.proxy.utils.IOUtils;

public class Configurator {
	private final String FILE_PROPERTIES = "cadbis_proxy.properties";
	private final String PROP_DENIED_ACCESS_FILE = "denied_access_file";
	private final Logger logger = LoggerFactory.getLogger(getClass());
	private String file_denied_access = "";
	private static Configurator instance=null;
	private Properties properties = null;
	private Configurator()
	{
		properties = new Properties();
	    try {
	    	reloadData();
	    	file_denied_access = new String(new IOUtils().readStreamAsString(
	    			Thread.currentThread().getContextClassLoader().getResourceAsStream(
	    					properties.getProperty(PROP_DENIED_ACCESS_FILE))));
	    	CADBiSThread.setCompleteGC(getProperty("thread_execgc").equals("true"));
	    } 
	    catch (IOException e) 
	    {
	    	logger.error("Error while reading properties: " + e.getMessage());
	    }
	}
	
	public static Configurator getInstance()
	{
		if(instance == null)
			instance = new Configurator();
		return instance;
	}
	
	
	public void reloadData() throws IOException 
	{
		properties.load(Thread.currentThread().getContextClassLoader().getResourceAsStream(FILE_PROPERTIES));
	}
	
	public String getProperty(String key)
	{
		if(properties.containsKey(key))
			return properties.getProperty(key);
		return "";
	}
	
	public InputStream getFileDeniedAccessAsStream()
	{
		return Thread.currentThread().getContextClassLoader().getResourceAsStream(properties.getProperty(PROP_DENIED_ACCESS_FILE));
	}

	public String getFile_denied_access() {
		return file_denied_access;
	}
}
