package cadbis.jradius;

import cadbis.CADBiSConfigurator;

public class JRadiusConfigurator extends CADBiSConfigurator {
	
	private static JRadiusConfigurator instance=null;
	private JRadiusConfigurator()
	{
		super("cadbis_jradius.properties");
	}
	
	public static JRadiusConfigurator getInstance()
	{
		if(instance == null)
			instance = new JRadiusConfigurator();
		return instance;
	}	
}
