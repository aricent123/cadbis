package cadbis.bl;

public class Protocol implements BusinessObject {
	private int pid;
	private String unique_id;	
	private String data;
	private long length;
	
	public Protocol(){};
	
	public Protocol(int pid, String unique_id, String data, long length) {
		super();
		this.pid = pid;
		this.unique_id = unique_id;
		this.data = data;
		this.length = length;
	}

	public String[][] getPerstistenceFields() {
		String[][] fields = {
				{"pid",			"Integer"},
				{"unique_id",	"String"},
				{"data",		"String"},
				{"length",		"Long"},
		};
		return fields;
	}

	public String getUnique_id() {
		return unique_id;
	}

	public void setUnique_id(String unique_id) {
		this.unique_id = unique_id;
	}

	public Long getLength() {
		return length;
	}

	public void setLength(Long length) {
		this.length = length;
	}

	public Object getPid() {
		return pid;
	}

	public void setPid(Integer pid) {
		this.pid = pid;
	}

	public String getData() {
		return data;
	}
	
	public void appendData(String value) {
		data+=value;
	}
	public void appendLength(long appendLength){
		this.length += appendLength;
	}

	public void setData(String data) {
		this.data = data;
	}



}
