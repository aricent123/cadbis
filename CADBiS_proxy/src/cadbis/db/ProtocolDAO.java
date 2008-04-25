package cadbis.db;

import cadbis.bl.Protocol;

public class ProtocolDAO extends AbstractDAO<Protocol> {

	public ProtocolDAO()
	{
		super(DBConnection.getInstance(), "protocols");
	}
	
}
