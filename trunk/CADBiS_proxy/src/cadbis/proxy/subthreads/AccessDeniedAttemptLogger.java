package cadbis.proxy.subthreads;

import cadbis.CADBiSThread;
import cadbis.proxy.Collector;

public class AccessDeniedAttemptLogger extends CADBiSThread {
 	private String HttpHost = "";
 	private String UserIp = "";
 	private Integer cid = 0;
 	public static enum Type{DENIED_CATEGORY,DENIED_URL};
 	private Type type = Type.DENIED_URL;
 	public AccessDeniedAttemptLogger(String HttpHost, String UserIp)
 	{
 		this.type = Type.DENIED_URL;
 	 	this.HttpHost = HttpHost;
 	 	this.UserIp = UserIp;
 	}
 	public AccessDeniedAttemptLogger(Integer cid, String HttpHost, String UserIp)
 	{
 		this.type = Type.DENIED_CATEGORY;
 	 	this.HttpHost = HttpHost;
 	 	this.UserIp = UserIp;
 	 	this.cid = cid;
 	} 	
 	
	public void run()
	{	
		switch(type){
			case DENIED_URL:
				Collector.getInstance().AddDeniedAccessAttemptUrl(UserIp, HttpHost);
			break;
			case DENIED_CATEGORY:
				Collector.getInstance().AddDeniedAccessAttemptCategory(UserIp, HttpHost, cid);
			break;
			
		}
		complete();
	}
	

}
