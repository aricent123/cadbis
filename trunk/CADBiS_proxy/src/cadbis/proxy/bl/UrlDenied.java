package cadbis.proxy.bl;

public class UrlDenied implements BusinessObject {
	private Integer duid;
	private Integer gid;
	private Integer url;
	
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

	public void setDuid(Integer duid) {
		this.duid = duid;
	}

	public Integer getGid() {
		return gid;
	}

	public void setGid(Integer gid) {
		this.gid = gid;
	}

	public Integer getUrl() {
		return url;
	}

	public void setUrl(Integer url) {
		this.url = url;
	}

}
