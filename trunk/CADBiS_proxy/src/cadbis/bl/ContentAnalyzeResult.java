package cadbis.bl;

import java.util.HashMap;


public class ContentAnalyzeResult {
	public HashMap<Integer, Integer> cats_coefs = new HashMap<Integer, Integer>();
	public HashMap<String, Integer> keywords = new HashMap<String, Integer>();
		
	public ContentAnalyzeResult() {
		super();
	}
	
	public ContentAnalyzeResult(HashMap<Integer, Integer> cats_coefs, HashMap<String, Integer> keywords) {
		super();
		this.cats_coefs = cats_coefs;
		this.keywords = keywords;
	}
	
	
}
