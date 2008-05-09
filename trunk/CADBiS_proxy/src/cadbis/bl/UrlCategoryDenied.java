package cadbis.bl;


public class UrlCategoryDenied implements BusinessObject {
	public Integer cid;
	public Integer gid;

	public String[][] getPerstistenceFields() {
		String[][] fields = {
				{"cid",		"Integer"},
				{"gid",		"Integer"},
		};
		return fields;		
	}

	public Integer getCid() {
		return cid;
	}

	public void setCid(Integer cid) {
		this.cid = cid;
	}

	public Integer getGid() {
		return gid;
	}

	public void setGid(Integer gid) {
		this.gid = gid;
	}
	
}
