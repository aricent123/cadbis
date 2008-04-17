package cadbis.proxy.bl;

import java.util.Date;

public class CollectedData {
	public String url;
	public Long bytes;
	public Date date;
	
	public CollectedData(String url, Long bytes, Date date)
	{
		this.url = url;
		this.bytes = bytes;
		this.date = date;
	}
}
