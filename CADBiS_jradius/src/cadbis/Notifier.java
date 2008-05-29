package cadbis;

import java.io.BufferedOutputStream;
import java.io.IOException;
import java.io.OutputStream;
import java.net.Socket;
import java.net.UnknownHostException;

import cadbis.jradius.JRadiusConfigurator;

public class Notifier extends CADBiSSubprocess {
	private String message = "";
	
	public Notifier(String login, String framedIP, String clientIP, String message) {
		super(login, framedIP, clientIP);
		this.message = message;
	}

	@Override
	public void run() {


		if(!clientIP.isEmpty())
		{
			 Integer portTo = Integer.parseInt(JRadiusConfigurator.getInstance().getProperty("send2ip_port"));

			 if(JRadiusConfigurator.getInstance().getProperty("send_by_self").equals("enabled"))
			 {
			 
				 Socket toServer;
				try {
					toServer = new Socket(clientIP,portTo);
					OutputStream serverOut = new BufferedOutputStream(toServer.getOutputStream());
					serverOut.write(message.getBytes());
					toServer.close();
				} catch (UnknownHostException e) {
					logger.error("Send2ip Unknown host error: " + e.getMessage());
				} catch (IOException e) {
					logger.error("Send2ip error: " + e.getMessage());
				}
			 }
			 else
			 {
				 try {
					 String execStr = JRadiusConfigurator.getInstance().getProperty("send_program");
					 execStr = String.format(execStr+" %s %s '%s'", clientIP, portTo, message);
					 Process p = Runtime.getRuntime().exec(execStr);
					 p.waitFor();
					 logger.info("Execute send2ip program result: " + p.exitValue());
				 }
				catch (Exception e) {
					logger.error("Error executing send2ip program: " + e.getMessage());
				}				 
			 }
			 
		}			
	}
}
