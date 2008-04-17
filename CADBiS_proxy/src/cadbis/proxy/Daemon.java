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
				Collector.getInstance().RefreshInfo();
				try{
					Thread.currentThread().sleep(Integer.valueOf(Configurator.getInstance().getProperty("DaemonPeriod")));
				}
				catch (InterruptedException e) {
					logger.error("Daemon thread terminated! " +e.getMessage());
				}
				catch (NumberFormatException e) {
					logger.error("Configuration error! " + e.getMessage());
				}
				Collector.getInstance().FlushCollected();
			}	
		}
	}
}
