package cadbis.proxy;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class Daemon extends Thread{
	private final Logger logger = LoggerFactory.getLogger(getClass());
	private static Daemon instance = null;
	private static Object dLock = new Object();
	
	@SuppressWarnings("static-access")
	private Daemon()
	{
		new Thread(){
			@Override
			public void run() {
				while(true)
				{
					try{
						logger.info("GC run...");
						System.gc();
						logger.info("GC completed...");
						int gcPeriod = Integer.valueOf(Configurator.getInstance().getProperty("gcperiod"));
						Thread.currentThread().sleep(gcPeriod);
					}
					catch (InterruptedException e) {
						logger.error("Daemon GC thread terminated! " +e.getMessage());
					}
				}
			}
		}.start();
	}
	
	public static Daemon getInstance()
	{
		if(instance == null)
			instance = new Daemon();
		return instance;
	}	
	
	@SuppressWarnings("static-access")
	@Override
	public void run() {	
		synchronized (dLock) {
			while(true)
			{
				logger.debug("Refreshing the sessions info.");
				Collector.getInstance().RefreshInfo();
				try{					
					Integer dPeriod = Integer.valueOf(Configurator.getInstance().getProperty("daemonperiod"));
					logger.debug("Sleep for " + dPeriod +" ms");
					Thread.currentThread().sleep(dPeriod);
				}
				catch (InterruptedException e) {
					logger.error("Daemon thread terminated! " +e.getMessage());
				}
				catch (NumberFormatException e) {
					logger.error("Configuration error! " + e.getMessage());
				}
				logger.debug("Waking up, flushing info.");				
				Collector.getInstance().FlushCollected();
			}	
		}
	}
}
