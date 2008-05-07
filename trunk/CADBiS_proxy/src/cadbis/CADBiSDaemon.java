package cadbis;

public abstract class CADBiSDaemon extends CADBiSThread {
	protected static CADBiSDaemon instance = null;
	private Object dLock = new Object();
	private int delay = 60000; 
	
	protected CADBiSDaemon() {
		
	}
	
	protected CADBiSDaemon(String name, int delay)
	{
		setName(name);
		this.delay = delay;
		logger.debug("instance created");
	}

	
	protected void prerun(){}	
	protected void daemonize(){};
	protected void postdaemonize(){};
	
	@SuppressWarnings("static-access")
	protected void sleep(int time){
		try{					
			Thread.currentThread().sleep(time);
		}
		catch (InterruptedException e) {
			logger.error("thread terminated! " +e.getMessage());
		}
	}
	
	@SuppressWarnings("static-access")
	@Override
	public final void run() {
		synchronized (dLock) {
		prerun();
			while(true)
			{
				logger.debug("daemonize...");				
				daemonize();
				sleep(this.delay);
				postdaemonize();
			}	
		}		
	}
}
