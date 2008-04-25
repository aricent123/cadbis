package cadbis.bl;

public class UrlLog implements BusinessObject {
	private String unique_id;	
	private String user;
	private String url;
	private long length;
	private String date;
	private String ip;
	private String content_type;
	
	public String[][] getPerstistenceFields() {
		String[][] fields = {
				{"unique_id",	"String"},
				{"user",		"String"},
				{"url",			"String"},
				{"length",		"Long"},
				{"date",		"String"},
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

	public String getContent_type() {
		return content_type;
	}

	public void setContent_type(String content_type) {
		this.content_type = content_type;
	}


}
