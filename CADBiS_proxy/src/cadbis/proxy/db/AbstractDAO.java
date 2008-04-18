package cadbis.proxy.db;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.lang.reflect.Method;
import java.lang.reflect.ParameterizedType;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.proxy.bl.BusinessObject;


public abstract class AbstractDAO<objT extends BusinessObject> {
    private final Logger logger = LoggerFactory.getLogger(getClass());
	protected DBConnection dataAccess = null;
	protected String tableName;
	
	protected Class<objT> paramClass;

	@SuppressWarnings("unchecked")
	public AbstractDAO(DBConnection dataAccess, String tableName)
	{
		this.dataAccess = dataAccess;
		this.tableName = tableName;
		this.paramClass = (Class<objT>) ((ParameterizedType) getClass()
                .getGenericSuperclass()).getActualTypeArguments()[0];		
	}
	
	public Class<objT> getParamClass() {
		return paramClass;
	}	
	
	
	/**
	 * Returns the items by any query
	 * @param query
	 * @return
	 */
	@SuppressWarnings("unchecked")
	public List<objT> getItemsByQuery(String query)
	{
		ArrayList<objT> list = new ArrayList<objT>();
		if(dataAccess==null || dataAccess.getConnection()==null)
			return null;
		try
		{
		   Statement s = dataAccess.getConnection().createStatement();
		   s.executeQuery (query);
		   ResultSet rs = s.getResultSet ();
		   while (rs.next ())
		   {
			   objT row = null;
			   try
			   {
				   row = (objT)Class.forName(getParamClass().getName()).newInstance();
			   }
			   catch(Exception e)
			   {
				   logger.error("Reflection error: " + e.getMessage());
			   }
			   
			   String[][] persistFields = row.getPerstistenceFields(); 	
			   
			   for(int i=0;i<persistFields.length;++i)
			   {
				   Class[] par=new Class[1];
				   par[0]= Object.class;
				   String persistName = persistFields[i][0].substring(0,1).toUpperCase()+persistFields[i][0].substring(1);
				   try
				   {
					   if(rs.findColumn(persistFields[i][0]) >= 0)
					   {
						   Method mthd=row.getClass().getMethod("set"+persistName,par);
						   if(persistFields[i][1] == "String")
							   mthd.invoke(row, rs.getString(persistFields[i][0]));
						   else if(persistFields[i][1] == "Integer")
							   mthd.invoke(row, rs.getInt(persistFields[i][0]));
						   else if(persistFields[i][1] == "Date")
							   mthd.invoke(row, rs.getDate(persistFields[i][0]));
					   }
					   
					   					   
				   }
				   catch( NoSuchMethodException e)
				   {
					   logger.error("Error! Method 'set"+persistName+"' for class '"+row.getClass().getName()+"' must be implemented!");
				   }
				   catch(SQLException e)
				   {
					   /* ignore */
				   }
				   catch(Exception e)
				   {
					   logger.error("Reflection error!  " + e.getMessage());
				   }
			   }
			   list.add(row);
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

	
	/**
	 * Execute sql query
	 * @param sql
	 */
	public void execSql(String sql)
	{
		if(dataAccess==null || dataAccess.getConnection()==null)
			return;
		try
		{		
			Statement s = dataAccess.getConnection().createStatement();
			s.executeUpdate (sql);
		}
		catch(SQLException e)
		{
			logger.error("Query execution error: " + e.getMessage());
		}			
	}
	
	/**
	 * Returns the instances count
	 * @return count
	 */
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