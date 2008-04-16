package cadbis.proxy.db;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.lang.reflect.Method;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public abstract class AbstractDbObject<objT, idT> {
    private final Logger logger = LoggerFactory.getLogger(getClass());
	protected DataAccess dataAccess = null;
	protected String tableName;
	
	public AbstractDbObject(DataAccess dataAccess, String tableName)
	{
		this.dataAccess = dataAccess;
		this.tableName = tableName;
	}
	
	
	public objT getItem(idT id)
	{
		return (objT)new Object();
	}
		
	public List<objT> getItemsByQuery(String query, String[] persistFields)
	{
		ArrayList<objT> list = new ArrayList<objT>();
		if(dataAccess==null || dataAccess.getConnection()==null)
			return null;
		try
		{
		   Statement s = dataAccess.getConnection().createStatement();
		   s.executeQuery (query);
		   ResultSet rs = s.getResultSet ();
		   int count = 0;
		   while (rs.next ())
		   {
			   objT row = (objT)new Object();
			   for(int i=0;i<persistFields.length;++i)
			   {
				   Class[] par=new Class[1];
				   par[0]= Object.class;
				   try
				   {
					   Method mthd=row.getClass().getMethod("set"+persistFields[i].substring(1,1)+persistFields[i].substring(1),par);
					   mthd.invoke(row, rs.getObject(persistFields[i]));
					   list.add(row);
				   }
				   catch( NoSuchMethodException e)
				   {
					   logger.error("Error! Method 'set"+persistFields[i]+"' for class '"+row.getClass().getName()+"' must be implemented!");
				   }
				   catch(Exception e)
				   {
					   logger.error("Reflection error!  " + e.getMessage());
				   }
			   }
		       ++count;
		   }
		   rs.close ();
		   s.close ();
		}
		catch(SQLException e)
		{
			logger.error("Query execution error: " + e.getMessage());
		}		
		
		return list;
	}
	
	public int getCount()
	{
		int count = 0;
		if(dataAccess==null)
			return 0;
		try
		{
		   Statement s = dataAccess.getConnection().createStatement();
		   s.executeQuery ("SELECT count(1) as count FROM " + tableName);
		   ResultSet rs = s.getResultSet ();
		   rs.next ();
		   count = rs.getInt ("count");
		   rs.close ();
		   s.close ();
	
		}
		catch(SQLException e)
		{
			logger.error("Query execution error: " + e.getMessage());
		}
		
		return count;
	}
}
