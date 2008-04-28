package cadbis;

public class Killer extends CADBiSSubprocess {
	private String nasPort = "";	
	public Killer(String login, String framedIP, String clientIP, String nasPort) {
		super(login, framedIP, clientIP);
		this.nasPort = nasPort;
	}

	@Override
	public void run() {
		if(!forLogin.isEmpty() && !framedIP.isEmpty())
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
					 execStr = String.format(execStr+" %s %s %s %s", forLogin, mpd_host, clientIP, nasPort);
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
