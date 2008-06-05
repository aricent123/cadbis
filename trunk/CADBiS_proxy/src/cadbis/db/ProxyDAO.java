package cadbis.db;

import java.math.BigDecimal;

import cadbis.bl.BusinessObjectImpl;

public class ProxyDAO extends AbstractDAO<BusinessObjectImpl> {
	public ProxyDAO() {
		super(DBConnection.getInstance(), "cadbis_tmp");
	}
 
	public void setChannelLoading(long loading)
	{
		execSql(String.format("update `cadbis_tmp` set `cvalue`=%d where `ckey`='current_channel_loading'",loading));
	}
	
	public void setMemoryUsage(long usage)
	{
		execSql(String.format("update `cadbis_tmp` set `cvalue`=%d where `ckey`='current_memory_usage'",usage));
	}	
}
