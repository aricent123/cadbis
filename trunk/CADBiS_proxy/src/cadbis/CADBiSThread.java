package cadbis;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class CADBiSThread extends Thread {
	protected static int thrCount = 0;
	protected final Logger logger = LoggerFactory.getLogger(getClass());
	protected static boolean completegc = false;
	
	public static void setCompleteGC(boolean value )
	{
		completegc = value;
	}
	
	private void created(){
 	 	synchronized (getClass()) {
 	 		thrCount++;
 	 		logger.debug("Thread "+this.getClass().getName()+".new, total = "+thrCount); 	 		
		}
	}
	public CADBiSThread() {
		created();
	}
	
	protected void complete()
	{
		if(completegc)
			System.gc();
	}
	
	@Override
	protected void finalize() throws Throwable {
	 	synchronized (getClass()) {
	 		thrCount--;
	 		logger.debug("Thread "+this.getClass().getName()+".finalize, total="+thrCount); 	 		
		}
		super.finalize();
	}	
}
