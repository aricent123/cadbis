package cadbis.proxy;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class Daemon extends Thread{
	private final Logger logger = LoggerFactory.getLogger(getClass());
	private static Daemon instance = null;
	private static Object dLock = new Object();
	
	private Daemon()
	{
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
					Integer dPeriod = Integer.valueOf(Configurator.getInstance().getProperty("DaemonPeriod"));
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
