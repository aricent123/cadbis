package cadbis.proxy;


import cadbis.db.ContentCategoryDAO;
import cadbis.utils.StringUtils;

class Test {	
  public static void main (String[] args)
  {
	  String title = "войти�в�почту";
//	  try {
//		title = new String(title.getBytes(StringUtils.UTF_CHARSET),StringUtils.ISO_CHARSET);
//	  } catch (UnsupportedEncodingException e) {
//		e.printStackTrace();
//	  }
	  String allowedChars = "абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghiklmnopqrstuvwxyz ";
	  for(int i=0;i<title.length();++i)
	  {
		  String tChar = title.substring(i,i+1);
		  if(allowedChars.indexOf(tChar)<0){
			  title = title.replaceAll(tChar, " ");
			  i = 0;
		  }
	  }
	  title = StringUtils.cyrUtfWin(title, false);
	  new ContentCategoryDAO().updateContentCategory(0,title);
  }
}
