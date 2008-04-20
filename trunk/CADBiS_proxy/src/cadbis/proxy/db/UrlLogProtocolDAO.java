package cadbis.proxy.db;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

import cadbis.proxy.bl.UrlLogProtocol;

public class UrlLogProtocolDAO extends AbstractDAO<UrlLogProtocol> {

	
	/**
	 * Returns the instances count
	 * @return count
	 */
	public List<String> getUniqueIds()
	{
		if(dataAccess==null)
			return null;
		List<String> keys = new ArrayList<String>();
		try
		{
		   Statement s = dataAccess.getConnection().createStatement();
		   s.executeQuery ("SELECT unique_id FROM " + tableName+" group by unique_id");
		   ResultSet rs = s.getResultSet ();
		   while (rs.next ())
		   {
			   keys.add(rs.getString("unique_id"));
		   }
		   rs.close ();
		   s.close ();
	
		}
		catch(SQLException e)
		{
			logger.error("Query execution error: " + e.getMessage());
		}		
		return keys;
	}
	
	public UrlLogProtocolDAO()
	{
		super(DBConnection.getInstance(), "url_log");
	}
	
}
