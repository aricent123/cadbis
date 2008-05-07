package cadbis.proxy;

import java.io.IOException;
import java.net.*;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.CADBiSThread;

public class Proxy extends CADBiSThread {
	private final static Logger logger = LoggerFactory.getLogger("Main");
	static int clientCount;

	@Override
	public void run() {
		super.run();
		try
		{
			String bindhost =  ProxyConfigurator.getInstance().getProperty("bindhost");
			int bindport = Integer.parseInt(ProxyConfigurator.getInstance().getProperty("bindport"));
			String fwdhost = ProxyConfigurator.getInstance().getProperty("fwdhost");
			int fwdport = Integer.parseInt(ProxyConfigurator.getInstance().getProperty("fwdport"));
			int timeout = Integer.parseInt(ProxyConfigurator.getInstance().getProperty("timeout"));
			
			if(ProxyConfigurator.getInstance().getProperty("collector").equals("enabled"))
				Collector.getInstance().start();
			
			if(ProxyConfigurator.getInstance().getProperty("gcrunner").equals("enabled"))
				GCRunner.getInstance().start();
				
			if(ProxyConfigurator.getInstance().getProperty("reconfigurer").equals("enabled"))	
				Reconfigurer.getInstance().start();
			
			if(ProxyConfigurator.getInstance().getProperty("aggregator").equals("enabled"))	
				Aggregator.getInstance().start();				
			
			this.run(bindhost,bindport,fwdhost,fwdport,timeout);							
		}
		catch(Exception e)
		{
			logger.error(e.getMessage());
		}			
	}
	
	
	public void run(String bindhost, int bindport, String fwdhost, int fwdport,long timeout) {
		try 
		{		
			boolean trueProxy = (ProxyConfigurator.getInstance().getProperty("trueproxy").equals("enabled"));
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
			 catch(IOException e)
			 {
				 logger.error("Binding to "+bindhost+":"+bindport+" error: "+e.getMessage());
				 return;
			 }
			 logger.info("listening to " + String.valueOf(bindport)+"...");
			while(true) 
			{			
				Socket cSocket=null;
				try 
				{
					cSocket = sSocket.accept();				
					if(cSocket!=null) 
					{
						logger.debug("accepted as #"+clientCount+":"+cSocket);
						clientCount++;
						ProxyConnection c = null;
						if(trueProxy)
							c = new ProxyConnection(cSocket,timeout);
						else
							c = new ProxyConnection(cSocket,fwdhost,fwdport,timeout);
						c.start();
					}
					logger.debug("Memory usage: "+((Runtime.getRuntime().totalMemory()-Runtime.getRuntime().freeMemory())/1024)+"Kb");
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
		try
		{
			Proxy self = new Proxy();
			self.run();
		}
		catch(Exception e)
		{
			logger.error("Error occured while starting the proxy: " + e.getMessage());
		}
	}
}
