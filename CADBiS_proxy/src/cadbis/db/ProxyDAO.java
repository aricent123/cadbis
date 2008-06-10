package cadbis.db;

import cadbis.bl.BusinessObjectImpl;

public class ProxyDAO extends AbstractDAO<BusinessObjectImpl> {
	public ProxyDAO() {
		super(DBConnection.getInstance(), "cadbis_tmp");
	}
 
	public void startStats()
	{
		execSql(String.format("delete from `cadbis_tmp` where `ckey`='current_channel_loading'"));
		execSql(String.format("delete from `cadbis_tmp` where `ckey`='current_memory_usage'"));
		execSql(String.format("insert into `cadbis_tmp` values('current_channel_loading',0)"));
		execSql(String.format("insert into `cadbis_tmp` values('current_memory_usage',0)"));
		
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
