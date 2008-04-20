package cadbis.proxy;

import java.io.IOException;

import cadbis.CADBiSThread;

public class Daemon extends CADBiSThread{
	private static Daemon instance = null;
	private static Object dLock = new Object();
	@SuppressWarnings("static-access")
	private Daemon()
	{
	if(Configurator.getInstance().getProperty("execgc").equals("true"))
		new CADBiSThread(){
			@Override
			public void run() {
				this.setName("GCRunner");
				int gcPeriod = Integer.valueOf(Configurator.getInstance().getProperty("gcperiod"));				
				while(true)
				{
					try{
						System.gc();
						logger.info("GCRunner: GC run completed.");
						Thread.currentThread().sleep(gcPeriod);
					}
					catch (InterruptedException e) {
						logger.error("Daemon GC thread terminated! " +e.getMessage());
					}
				}
			}
		}.start();
		
	if(Configurator.getInstance().getProperty("reconfigure").equals("true"))	
		new CADBiSThread(){
			 @Override
			public void run() {
				this.setName("Reconfigurer");
				int confPeriod = Integer.valueOf(Configurator.getInstance().getProperty("confperiod"));				 
				while(true)
				{
					try{
						Thread.currentThread().sleep(confPeriod);
						Configurator.getInstance().reloadData();
						logger.info("Reconfigurer: Config reloaded.");
					}
					catch (IOException e) {
						logger.error("Failed to load config: " + e.getMessage());
					}
					catch (InterruptedException e) {
						logger.error("Daemon Conf thread terminated! " +e.getMessage());
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
		this.setName("Daemon");
		synchronized (dLock) {
			while(true)
			{
				logger.debug("Refreshing the sessions info.");
				Collector.getInstance().RefreshInfo();
				try{					
					Integer dPeriod = Integer.valueOf(Configurator.getInstance().getProperty("daemon_period"));
					logger.debug("Sleep for " + dPeriod +" ms");
					Thread.currentThread().sleep(dPeriod);
				}
				catch (InterruptedException e) {
					logger.error("Daemon thread terminated! " +e.getMessage());
				}
				catch (NumberFormatException e) {
					logger.error("Configuration error! " + e.getMessage());
				}
				logger.info("Waking up, flushing info.");				
				Collector.getInstance().FlushCollected();
			}	
		}
	}
}
