package cadbis.proxy;

import java.io.IOException;
import java.util.Properties;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class Configurator {
	private final String FILE_PROPERTIES = "cadbis_proxy.properties";
	private final Logger logger = LoggerFactory.getLogger(getClass());
	private static Configurator instance=null;
	private Properties properties = null;
	private Configurator()
	{
		properties = new Properties();
	    try {
	    	properties.load(Thread.currentThread().getContextClassLoader().getResourceAsStream(FILE_PROPERTIES));
	    } 
	    catch (IOException e) 
	    {
	    	logger.error("Could not read properties file: " + e.getMessage());
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
}
