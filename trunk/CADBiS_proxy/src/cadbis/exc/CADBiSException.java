package cadbis.exc;

import cadbis.proxy.ProxyConfigurator;

public abstract class CADBiSException extends Exception {
	private static final long serialVersionUID = 1L;
	public CADBiSException(String message)
	{
		super(message);
	}
	
	public String getUniformMessage() {
		return ProxyConfigurator.getInstance().getProperty(getClass().getSimpleName());
	}
}
