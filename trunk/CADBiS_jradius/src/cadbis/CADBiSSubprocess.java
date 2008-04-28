package cadbis;

public abstract class CADBiSSubprocess extends CADBiSThread{
	protected String forLogin = "";
	protected String framedIP = "";
	protected String clientIP = "";
	
	public CADBiSSubprocess(String login, String framedIP, String clientIP)
	{
		forLogin = login;
		this.framedIP = framedIP;
		this.clientIP = clientIP;
	}
	
	@Override
	public abstract void run();
}
