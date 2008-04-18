package cadbis.proxy.bl;

public class UrlDenied implements BusinessObject {
	private Integer duid;
	private Integer gid;
	private String url;
	
	public String[][] getPerstistenceFields() {
		String[][] fields = {
				{"duid",		"Integer"},
				{"gid",			"Integer"},
				{"url",			"String"},
		};
		return fields;
	}

	public Integer getDuid() {
		return duid;
	}

	public void setDuid(Object duid) {
		this.duid = (Integer)duid;
	}

	public Object getGid() {
		return gid;
	}

	public void setGid(Object gid) {
		this.gid = (Integer)gid;
	}

	public Object getUrl() {
		return url;
	}

	public void setUrl(Object url) {
		this.url = (String)url;
	}

}
