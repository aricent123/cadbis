package cadbis.proxy;

import java.io.IOException;

import cadbis.CADBiSDaemon;
import cadbis.db.DBConnection;

public class Reconfigurer extends CADBiSDaemon{	
	private static Reconfigurer instance = null;
	protected Reconfigurer() {
		super("Reconfigurer",Integer.valueOf(ProxyConfigurator.getInstance().getProperty("reconf_period")));
	}

	public static Reconfigurer getInstance(){
		if(instance == null)
			instance = new Reconfigurer();
		return (Reconfigurer)instance;
	}	
	
	@Override
	protected void postdaemonize() {
		if(ProxyConfigurator.getInstance().getProperty("db_reconnect").equals("enabled"))
			DBConnection.getInstance().Reconnect();
	}
	
	@Override
	protected void daemonize() {
		try{
			ProxyConfigurator.getInstance().reloadData();
			logger.info("Reconfigurer: Config reloaded.");
		}
		catch (IOException e) {
			logger.error("Failed to load config: " + e.getMessage());
		}		
	}
}
