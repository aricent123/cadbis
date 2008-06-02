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
	
	public List<Packet> getPacketsWithStats()
	{
		return getItemsByQuery("select p.*, count(u.uid) as users_count, sum(u.simultaneous_use) as simuluse_sum from `packets` p inner join `users` u on u.gid = p.gid group by u.gid");
	}
	
	public Packet getPacketWithStats(Integer gid)
	{
		return getItemByQuery(String.format("select p.*, count(u.uid) as users_count, sum(u.simultaneous_use) as simuluse_sum from `packets` p inner join `users` u on u.gid = p.gid where p.gid=%d group by u.gid",gid));
	}	
	
	public Long getMonthTraffic()
	{
		Object value = getSingleValueByQuery(String.format("select sum(a.out_bytes) as traffic from `actions` a  where UNIX_TIMESTAMP(a.start_time) > UNIX_TIMESTAMP(NOW()) -%d*3600*24",DateUtils.getDOM()),"traffic");
		if(value == null)
			return 0L;
		else
			return ((BigDecimal)value).longValue();		
	}
	
	public Long getDayTraffic(Integer gid)
	{
		Object value = getSingleValueByQuery(String.format("select sum(a.out_bytes) as traffic from `actions` a  where a.start_time > '%s 00:00:00' and a.stop_time < '%s 23:59:59' and gid=%d",DateUtils.getDateForSql(),DateUtils.getDateForSql(),gid),"traffic");
		if(value == null)
			return 0L;
		else
			return ((BigDecimal)value).longValue();
	}	
}
