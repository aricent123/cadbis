package cadbis;

import cadbis.jradius.JRadiusConfigurator;

public class Killer extends CADBiSSubprocess {
	private Integer nasPort = 0;	
	public Killer(String login, String framedIP, String clientIP, Integer nasPort) {
		super(login, framedIP, clientIP);
		this.nasPort = nasPort;
	}

	@Override
	public void run() {
		if(forLogin.length()>0 && framedIP.length()>0)
		{
			//Integer portTo = Integer.parseInt(JRadiusConfigurator.getInstance().getProperty("mpd_console_port"));
			String mpd_host = JRadiusConfigurator.getInstance().getProperty("mpd_console_host");
			
			 if(JRadiusConfigurator.getInstance().getProperty("kill_by_self").equals("enabled"))
			 {
				 
			 }
			 else
			 {
				 try {
					 String execStr = JRadiusConfigurator.getInstance().getProperty("kill_program");
					 execStr = String.format(execStr+" %d", nasPort);
					 Process p = Runtime.getRuntime().exec(execStr);
					 p.waitFor();
					 logger.info("Execute kill program result: " + p.exitValue());
				 }
				catch (Exception e) {
					logger.error("Error executing kill program: " + e.getMessage());
				}				 
			 }			
		}		
	}
	
	
}
