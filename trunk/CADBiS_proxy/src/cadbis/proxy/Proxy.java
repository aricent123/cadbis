package cadbis.proxy;

import java.net.*;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class Proxy {
	private final static Logger logger = LoggerFactory.getLogger("Main");
	static int clientCount;

public void run(String bindhost, int bindport, String fwdhost, int fwdport,long timeout) {
	try 
	{		
		ServerSocket sSocket = null;
		 try
		 {
			 sSocket = new ServerSocket();
			 sSocket.bind(new InetSocketAddress(InetAddress.getByName(bindhost),bindport));
		 }
		 catch(UnknownHostException e)
		 {
			 logger.error("Unknown host: " + e.getMessage());
		 }		 
		

		while(true) 
		{
			logger.info("listening to " + String.valueOf(bindport)+"...");
			Socket cSocket=null;
			try 
			{
				cSocket = sSocket.accept();				
				if(cSocket!=null) 
				{
					logger.debug("accepted as #"+clientCount+":"+cSocket);
					clientCount++;
					ProxyConnection c = new ProxyConnection(cSocket,fwdhost,fwdport,timeout);
					c.start();
				}
			} 
			catch(Exception e) 
			{
				e.printStackTrace(System.err);
			}    
		}
	} 
	catch(Throwable t) 
	{
		t.printStackTrace(System.err);
	}
}

	public static void main(String[] argv) 
	{		
		Proxy self = new Proxy();
			try
			{
				String bindhost =  Configurator.getInstance().getProperty("bindhost");
				int bindport = Integer.parseInt(Configurator.getInstance().getProperty("bindport"));
				String fwdhost = Configurator.getInstance().getProperty("fwdhost");
				int fwdport = Integer.parseInt(Configurator.getInstance().getProperty("fwdport"));
				int timeout = Integer.parseInt(Configurator.getInstance().getProperty("timeout"));
				Daemon.getInstance().start();
				self.run(bindhost,bindport,fwdhost,fwdport,timeout);							
			}
			catch(Exception e)
			{
				logger.error("");
			}
	}
}
