package cadbis.proxy.bl;

public class UrlLogProtocol implements BusinessObject {
	private String unique_id;	
	private String url;
	private long length;
	private String date;
	private int count;
	private String user;
	private String ip;
	private String content_type;	
	
	
	public UrlLogProtocol(){
		
	}
	
	public UrlLogProtocol(String unique_id, String url, long length,
			String date, int count, String user, String ip, String content_type) {
		super();
		this.unique_id = unique_id;
		this.url = url;
		this.length = length;
		this.date = date;
		this.count = count;
		this.user = user;
		this.ip = ip;
		this.content_type = content_type;
	}

	public String[][] getPerstistenceFields() {
		String[][] fields = {
				{"unique_id",	"String"},				
				{"url",			"String"},
				{"length",		"Long"},
				{"date",		"String"},
				{"count",		"Integer"},
				{"user",		"String"},
				{"ip",			"String"},
				{"content_type","String"},				
		};
		return fields;
	}

	public Object getUnique_id() {
		return unique_id;
	}

	public void setUnique_id(Object unique_id) {
		this.unique_id = (String)unique_id;
	}

	public Object getUser() {
		return user;
	}

	public void setUser(Object user) {
		this.user = (String)user;
	}

	public Object getUrl() {
		return url;
	}

	public void setUrl(Object url) {
		this.url = (String)url;
	}

	public Object getLength() {
		return length;
	}

	public void setLength(Object length) {
		this.length = (Long)length;
	}

	public Object getDate() {
		return date;
	}

	public void setDate(Object date) {
		this.date = (String)date;
	}

	public Object getIp() {
		return ip;
	}

	public void setIp(Object ip) {
		this.ip = (String)ip;
	}

	public Object getCount() {
		return count;
	}

	public void setCount(Object count) {
		this.count = (Integer)count;
	}

	public Object getContent_type() {
		return content_type;
	}

	public void setContent_type(Object content_type) {
		this.content_type = (String)content_type;
	}


	public void addCount(int count)
	{
		this.count += count;
	}
	
	public void addLength(long length)
	{
		this.length += length;
	}
}
