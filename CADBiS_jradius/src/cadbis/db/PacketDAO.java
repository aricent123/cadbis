package cadbis.db;

import java.math.BigDecimal;
import java.util.List;

import cadbis.bl.Packet;
import cadbis.utils.DateUtils;

public class PacketDAO extends AbstractDAO<Packet> {

	public PacketDAO()
	{
		super(DBConnection.getInstance(), "packets");
	}
	
	public List<Packet> getItemsWithStats()
	{
		return getItemsByQuery("select p.*, count(u.uid) as users_count, sum(u.simultaneouse_use) as simuluse_sum from `packets` p inner join `users` u on u.gid = p.gid group by u.gid");
	}
	
	public Long getMonthTraffic()
	{
		return ((BigDecimal)getSingleValueByQuery(String.format("select sum(a.in_bytes) as traffic from `actions` a  where UNIX_TIMESTAMP(a.start_time) > UNIX_TIMESTAMP(NOW()) -%d*3600*24",DateUtils.getDOM()),"traffic")).longValue();
	}
}
