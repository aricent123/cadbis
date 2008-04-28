package cadbis.proxy;

import cadbis.CADBiSDaemon;

public class GCRunner extends CADBiSDaemon{	
	private static GCRunner instance = null;
	protected GCRunner() {
		super("GCRunner",Integer.valueOf(ProxyConfigurator.getInstance().getProperty("gcrunner_period")));
	}

	public static GCRunner getInstance(){
		if(instance == null)
			instance = new GCRunner();
		return (GCRunner)instance;
	}	
	
	@Override
	protected void daemonize() {		
		System.gc();
		logger.info("GCRunner: GC run completed.");
	}
}
