package cadbis.proxy;

import java.io.IOException;
import java.util.Properties;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

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
	    	properties.load(Thread.currentThread().getContextClassLoader().getResourceAsStream(FILE_PROPERTIES));
	    	file_denied_access = IOUtils.readStreamAsString(
	    			Thread.currentThread().getContextClassLoader().getResourceAsStream(
	    					properties.getProperty(PROP_DENIED_ACCESS_FILE)));
	    	
	    	
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
	
	
	public String getProperty(String key)
	{
		return properties.getProperty(key);
	}

	public String getFile_denied_access() {
		return file_denied_access;
	}
}
