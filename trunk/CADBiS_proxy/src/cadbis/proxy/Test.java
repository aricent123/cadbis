package cadbis.proxy;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;


class Test {	
  protected static final Logger logger = LoggerFactory.getLogger("TestLogger");
  public static void main (String[] args)
  {
	  // jdk1.5
	  System.out.println(java.nio.charset.Charset.defaultCharset().name()); 
  }
}
