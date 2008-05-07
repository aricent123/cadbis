package cadbis.bl;

public class UrlCategoryMatch implements BusinessObject {
	protected Integer cid = 0;
	protected String url ="";
	
	public String[][] getPerstistenceFields() {
		String[][] fields = {
				{"cid", "Integer"},
				{"url", "String"},
		};
		return fields;
	}

	public Integer getCid() {
		return cid;
	}

	public void setCid(Integer cid) {
		this.cid = cid;
	}

	public String getUrl() {
		return url;
	}

	public void setUrl(String url) {
		this.url = url;
	}
	
}
