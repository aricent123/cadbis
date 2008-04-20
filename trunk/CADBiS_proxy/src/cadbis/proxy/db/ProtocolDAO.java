package cadbis.proxy.db;

import cadbis.proxy.bl.Protocol;

public class ProtocolDAO extends AbstractDAO<Protocol> {

	public ProtocolDAO()
	{
		super(DBConnection.getInstance(), "protocols");
	}
	
}
