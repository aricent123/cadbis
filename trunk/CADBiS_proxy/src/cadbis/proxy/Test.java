package cadbis.proxy;

import cadbis.bl.ContentCategory;
import cadbis.db.ContentCategoryDAO;
import cadbis.utils.StringUtils;


class Test {	
  public static void main (String[] args)
  {
	  String fuck="�����,�����(��),gti,��������������� ��������, ����������� �����������, ������(��), ������(��), ���������� ���, �����, ����������,  ����������, ������ �����������, �����, �����������, ���������, ���������, ������, ����������, ����������, �������, ��������, �����������, ������������, ���������, �������, ������ ������, �������, ��������, ���������� �� �����";
	  String fuckw="�����";
	  System.out.println(fuck.matches("(?ims)[^\\w]"+fuckw+"[^\\w]"));
  }
}
