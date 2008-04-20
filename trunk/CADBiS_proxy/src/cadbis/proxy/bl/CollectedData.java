package cadbis.proxy.bl;

import java.util.Date;

public class CollectedData {
	public String url;
	public Long bytes;
	public Date date;
	public String ip;
	public String content_type;
	
	public CollectedData(String url, Long bytes, Date date, String ip, String content_type)
	{
		this.url = url;
		this.bytes = bytes;
		this.date = date;
		this.ip = ip;
		this.content_type = content_type;
	}
}
