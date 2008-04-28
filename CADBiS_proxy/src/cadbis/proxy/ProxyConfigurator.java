package cadbis.proxy;

import java.io.IOException;
import java.io.InputStream;
import cadbis.CADBiSThread;
import cadbis.CADBiSConfigurator;
import cadbis.utils.IOUtils;

public class ProxyConfigurator extends CADBiSConfigurator{
	private final String PROP_DENIED_ACCESS_FILE = "denied_access_file";
	private String file_denied_access = "";
	private static ProxyConfigurator instance=null;
	private ProxyConfigurator()
	{
		super("cadbis_proxy.properties");
	    try {
	    	file_denied_access = new String(IOUtils.readStreamAsString(
	    			Thread.currentThread().getContextClassLoader().getResourceAsStream(
	    					properties.getProperty(PROP_DENIED_ACCESS_FILE))));
	    	CADBiSThread.setCompleteGC(getProperty("thread_execgc").equals("enabled"));
	    } 
	    catch (IOException e) 
	    {
	    	logger.error("Error while reading properties: " + e.getMessage());
	    }
	}
	
	public static ProxyConfigurator getInstance()
	{
		if(instance == null)
			instance = new ProxyConfigurator();
		return instance;
	}
	
	public InputStream getFileDeniedAccessAsStream()
	{
		return Thread.currentThread().getContextClassLoader().getResourceAsStream(properties.getProperty(PROP_DENIED_ACCESS_FILE));
	}

	public String getFile_denied_access() {
		return file_denied_access;
	}
}
