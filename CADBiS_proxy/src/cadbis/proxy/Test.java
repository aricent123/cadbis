package cadbis.proxy;

class Test {
	private static long MAX_TIMES = 9999;
	private static int SLEEP_TIME = 10;
	
  private static class MyThread extends Thread {
    public void run()
    {
    	try{
    		sleep(SLEEP_TIME);
    	}
    	catch(InterruptedException e)
    	{
    		
    	}
    }
  }

  public static void main (String[] args)
  {
	  try{
	  if(args.length>1){
		  SLEEP_TIME = Integer.valueOf(args[1]);
		  MAX_TIMES = Long.valueOf(args[0]);
	  }
	  for(long i=0;i<MAX_TIMES;++i) 
	  {
		  new MyThread().start();
		  try{
		  Thread.sleep(SLEEP_TIME);
		  }catch(InterruptedException e)
		  {
			  
		  }
	  }
	  }catch(Exception e)
	  {
		  System.out.println("error " + e.getMessage());
	  }
  }
}
