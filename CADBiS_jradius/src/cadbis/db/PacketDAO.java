package cadbis.db;

import cadbis.bl.Packet;

public class PacketDAO extends AbstractDAO<Packet> {

	public PacketDAO()
	{
		super(DBConnection.getInstance(), "packets");
	}
	
}
