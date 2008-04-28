package cadbis;

import java.io.IOException;
import java.util.Properties;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public abstract class CADBiSConfigurator {
	protected String PathToFile = "cadbis.properties";
	protected final Logger logger = LoggerFactory.getLogger(getClass());
	protected Properties properties = null;
	public CADBiSConfigurator(String path)
	{
		PathToFile = path;
		properties = new Properties();
	    try {
	    	reloadData();
	    } 
	    catch (IOException e) 
	    {
	    	logger.error("Error while reading properties: " + e.getMessage());
	    }
	}

	
	
	public void reloadData() throws IOException 
	{
		properties = new Properties();
		properties.load(Thread.currentThread().getContextClassLoader().getResourceAsStream(PathToFile));
	}
	
	public String getProperty(String key)
	{
		if(properties.containsKey(key))
			return properties.getProperty(key);
		return "";
	}
}
