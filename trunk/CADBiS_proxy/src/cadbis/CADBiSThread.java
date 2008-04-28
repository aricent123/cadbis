package cadbis;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class CADBiSThread extends Thread {
//	protected static int thrCount = 0;
	protected final Logger logger = LoggerFactory.getLogger(getClass());
//	protected static boolean completegc = false;
	
	public static void setCompleteGC(boolean value )
	{
//		completegc = value;
	}
	
	private void created(){
// 	 	synchronized (getClass()) {
// 	 		thrCount++; 	 		
//		}
//	 		logger.debug("Thread "+this.getClass().getName()+".new, total = "+thrCount); 	 	
	}
	public CADBiSThread() {
		setName(this.getClass().getSimpleName());
		created();
	}
	
	protected void complete()
	{
//	 	synchronized (getClass()) {
//	 		thrCount--;
//		}
//	 	logger.debug("Thread "+this.getClass().getName()+".complete, total="+thrCount);
//		if(completegc)
//			System.gc();	 	
	}
}
