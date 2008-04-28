package cadbis.proxy;


import cadbis.CADBiSDaemon;

public class Daemon extends CADBiSDaemon{
	private static Daemon instance = null;
	private Daemon()
	{
		super("Daemon",Integer.valueOf(ProxyConfigurator.getInstance().getProperty("daemon_period")));
	}
	
	public static Daemon getInstance()
	{
		if(instance == null)
			instance = new Daemon();
		return (Daemon)instance;
	}	
	
	@Override
	protected void daemonize() {
		logger.debug("Refreshing the sessions info.");
		Collector.getInstance().RefreshInfo();
		logger.info("Daemon : waking up, flushing info.");				
		Collector.getInstance().FlushCollected();		
	}
}
