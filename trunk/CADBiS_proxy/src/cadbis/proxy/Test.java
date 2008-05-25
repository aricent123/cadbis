package cadbis.proxy;

import cadbis.bl.ContentCategory;
import cadbis.db.ContentCategoryDAO;
import cadbis.utils.StringUtils;


class Test {	
  public static void main (String[] args)
  {
	  String fuck="спгти,спгти(ту),gti,технологический институт, технический университет, спбгти(ту), спбгти(ту), российский вуз, наука, технология,  техноложка, высшее образование, химия, кибернетика, менделеев, профессор, доктор, московский, информация, новости, лицензия, аспирантура, докторантура, факультет, кафедра, второе высшее, отрасль, обучение, посмотреть по карте";
	  String fuckw="наука";
	  System.out.println(fuck.matches("(?ims)[^\\w]"+fuckw+"[^\\w]"));
  }
}
