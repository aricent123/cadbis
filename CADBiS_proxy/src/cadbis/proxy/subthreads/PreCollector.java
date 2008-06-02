package cadbis.proxy.subthreads;

import java.io.IOException;
import java.net.InetAddress;
import java.util.Date;

import cadbis.CADBiSThread;
import cadbis.proxy.Collector;

public class PreCollector extends CADBiSThread {
 	private String HttpHost = "";
 	private String HostIp = "";
 	private long RcvBytes = 0;
 	private String UserIp = "";
 	private String ContentType = "";
 	
 	public PreCollector(String HttpHost, long RcvBytes, String UserIp, String ContentType, String HostIp)
 	{
 	 	this.HttpHost = HttpHost;
 	 	this.RcvBytes = RcvBytes;
 	 	this.UserIp = UserIp;
 	 	this.ContentType = ContentType;
 	 	this.HostIp = HostIp;
 	}
	public void run()
	{	
		if(HttpHost!= null && HostIp.length()==0)
		{	
			logger.debug("PreCollector doesn't know the host's ip... :" + HttpHost);
			try
			{
				InetAddress addr = InetAddress.getByName(HttpHost);
				HostIp = addr.getHostAddress(); 
			}
			catch(IOException e)
			{
				logger.error("PreCollector fails to recognize the host's ip address of '"+HttpHost+"': " + e.getMessage());
			}
			Collector.getInstance().Collect(UserIp, HttpHost, RcvBytes, new Date(), HostIp, ContentType);
		}		
		complete();
	}
	

}
