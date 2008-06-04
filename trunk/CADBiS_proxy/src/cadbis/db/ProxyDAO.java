package cadbis.db;

import java.math.BigDecimal;

import cadbis.bl.BusinessObjectImpl;

public class ProxyDAO extends AbstractDAO<BusinessObjectImpl> {
	public ProxyDAO() {
		super(DBConnection.getInstance(), "cadbis_tmp");
	}
 
	public void setChannelLoading(long loading)
	{
		execSql(String.format("replace into `cadbis_tmp` values ('current_channel_loading',%d)",loading));
	}
	
	public void setMemoryUsage(long usage)
	{
		execSql(String.format("replace into `cadbis_tmp` values('current_memory_usage',%d)",usage));
	}	
}
