package cadbis.proxy;

import java.io.IOException;
import java.net.Socket;
import java.util.Date;

import cadbis.CADBiSThread;

public class PreCollector extends CADBiSThread {
 	private String HttpHost = "";
 	private int HttpPort = 80;
 	private String HostIp = "";
 	private long RcvBytes = 0;
 	private String UserIp = "";
 	private String ContentType = "";
 	
 	public PreCollector(String HttpHost,int HttpPort, long RcvBytes, String UserIp, String ContentType, String HostIp)
 	{
 	 	this.HttpHost = HttpHost;
 	 	this.HttpPort = HttpPort;
 	 	this.RcvBytes = RcvBytes;
 	 	this.UserIp = UserIp;
 	 	this.ContentType = ContentType;
 	 	this.HostIp = HostIp;
 	}
	public void run()
	{	
		if(HttpHost!= null && HostIp.isEmpty())
		{	
			logger.info("PreCollector doesn't know the host's ip... :" + HttpHost);
			try
			{
				Socket dnsQuery = new Socket(HttpHost,HttpPort);
				HostIp = dnsQuery.getInetAddress().getHostAddress();
				dnsQuery.close();
			}
			catch(IOException e)
			{
				logger.error("PreCollector fails to recognize the host's ip address of '"+HttpHost+"': " + e.getMessage());
			}
		}
		Collector.getInstance().Collect(UserIp, HttpHost, RcvBytes, new Date(), HostIp, ContentType);
		complete();
	}
	

}
