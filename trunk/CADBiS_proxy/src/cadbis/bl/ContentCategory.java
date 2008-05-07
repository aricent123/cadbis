package cadbis.bl;

import java.util.List;

public class ContentCategory implements BusinessObject {
	protected Integer cid = 0;
	protected String title ="";
	
	protected List<String> keywords = null;
	
	public String[][] getPerstistenceFields() {
		String[][] fields = {
				{"cid", "Integer"},
				{"title", "String"},
		};
		return fields;
	}

	public Integer getCid() {
		return cid;
	}

	public void setCid(Integer cid) {
		this.cid = cid;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public List<String> getKeywords() {
		return keywords;
	}

	public void setKeywords(List<String> keywords) {
		this.keywords = keywords;
	}
	
}
