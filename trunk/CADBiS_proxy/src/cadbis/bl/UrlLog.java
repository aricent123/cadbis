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

	public String getUnique_id() {
		return unique_id;
	}

	public void setUnique_id(String unique_id) {
		this.unique_id = unique_id;
	}

	public String getUser() {
		return user;
	}

	public void setUser(String user) {
		this.user = user;
	}

	public String getUrl() {
		return url;
	}

	public void setUrl(String url) {
		this.url = url;
	}

	public Long getLength() {
		return length;
	}

	public void setLength(Long length) {
		this.length = length;
	}

	public String getDate() {
		return date;
	}

	public void setDate(String date) {
		this.date = date;
	}

	public String getIp() {
		return ip;
	}

	public void setIp(String ip) {
		this.ip = ip;
	}

	public String getContent_type() {
		return content_type;
	}

	public void setContent_type(String content_type) {
		this.content_type = content_type;
	}


}
