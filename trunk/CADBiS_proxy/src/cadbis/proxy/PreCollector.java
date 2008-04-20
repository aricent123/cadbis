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
 	
 	public PreCollector(String HttpHost,int HttpPort, long RcvBytes, String UserIp)
 	{
 	 	this.HttpHost = HttpHost;
 	 	this.HttpPort = HttpPort;
 	 	this.RcvBytes = RcvBytes;
 	 	this.UserIp = UserIp; 
 	}
	public void run()
	{	
			if(HttpHost!= null)
			{										
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
				Collector.getInstance().Collect(UserIp, HttpHost, RcvBytes, new Date(), HostIp);
		}
	}
	

}
