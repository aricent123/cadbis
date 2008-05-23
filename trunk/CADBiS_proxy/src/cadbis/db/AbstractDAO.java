package cadbis.db;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.lang.reflect.Method;
import java.lang.reflect.ParameterizedType;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.bl.BusinessObject;


public abstract class AbstractDAO<objT extends BusinessObject> {
    protected final Logger logger = LoggerFactory.getLogger(getClass());
	protected DBConnection dataAccess = null;
	protected String tableName;
	
	protected Statement state = null;
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
	
	protected void closeRsState(ResultSet rs, Statement s )
	{
		try{
			if(rs!=null)
				rs.close();
			if(s!=null)
				s.close();
			if(this.state!=null)
				this.state.close();
			
			}catch(SQLException e){}		
	}
	
	
	protected void closeRs(ResultSet rs)
	{
		closeRsState(rs,null);
	}

	protected void closeState(Statement s)
	{
		closeRsState(null,s);
	}
	
	
	protected ResultSet getResultSet(String query)
	{
		if(dataAccess==null || dataAccess.getConnection()==null)
			return null;
		ResultSet rs = null;
		try
		{			
			this.state = dataAccess.getConnection().createStatement();
			this.state.executeQuery (query);
			rs = this.state.getResultSet ();
			
		}
		catch(SQLException e)
		{
			logger.error("SQLError: " + e.getMessage());
			closeRsState(rs, this.state);
		}
		return rs;
	}
	
	
	/**
	 * Returns the items by any query
	 * @param query
	 * @return
	 */
	@SuppressWarnings("unchecked")
	public List<objT> getItemsByQuery(String query)
	{
		logger.debug(query);
		ArrayList<objT> list = new ArrayList<objT>();

		
		
		ResultSet rs = null;
		try
		{
		   rs = getResultSet(query);
		   while (rs!=null && rs.next ())
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
				   //par[0]= Object.class;
				   
				   String persistName = persistFields[i][0].substring(0,1).toUpperCase()+persistFields[i][0].substring(1);
				   try
				   {
					   if(rs.findColumn(persistFields[i][0]) >= 0)
					   {
						   try
						   {
							   par[0] = Class.forName("java.lang."+persistFields[i][1]);
							   Method mthd=row.getClass().getMethod("set"+persistName,par);
							   if(persistFields[i][1] == "String")
								   mthd.invoke(row, rs.getString(persistFields[i][0]));
							   else if(persistFields[i][1] == "Integer")
								   mthd.invoke(row, rs.getInt(persistFields[i][0]));
							   else if(persistFields[i][1] == "Long")
								   mthd.invoke(row, rs.getLong(persistFields[i][0]));						   
							   else if(persistFields[i][1] == "Date")
								   mthd.invoke(row, rs.getDate(persistFields[i][0]));						   
						   }
						   catch( NoSuchMethodException e)
						   {
							   logger.warn("Warning! Method 'set"+persistName+"' for class '"+row.getClass().getName()+"' should be implemented!");
						   }
					   }
					   
					   					   
				   }
				   catch(SQLException e)
				   {
					   logger.error("SQL exception for query '"+query+"'!  " + e.getMessage());
				   }
				   catch(Exception e)
				   {
					   logger.error("Reflection error for query '"+query+"'!  " + e.getMessage());
				   }
			   }
			   list.add(row);
		   }
		}
		catch(SQLException e)
		{
			logger.error("Query '"+query+"' execution error: " + e.getMessage());
		}	
		finally
		{
			closeRs(rs);
		}				
		
		return list;
	}

	/**
	 * Get single item by custom query
	 * @param query
	 * @return T
	 */
	public objT getItemByQuery(String query)
	{
		List<objT> tmp = getItemsByQuery(query);
		if(tmp.size()!=1)
			return null;
		return tmp.get(0);
	}
	
	/**
	 * Execute sql query
	 * @param sql
	 */
	public void execSql(String query)
	{
		logger.debug(query);
		if(dataAccess==null || dataAccess.getConnection()==null)
			return;
		
		Statement s = null;				
		try
		{		
			s = dataAccess.getConnection().createStatement();
			s.executeUpdate (query);
		}
		catch(SQLException e)
		{
			logger.error("Query '"+query+"' execution error: " + e.getMessage());
		}
		finally
		{
			closeState(s);
		}		
	}
	
	/**
	 * Returns the instances count
	 * @return count
	 */
	public int getCountByQuery(String query, String key)
	{
		logger.debug(query);
		int count = 0;
		if(dataAccess==null)
			return 0;
		
		ResultSet rs = null;
		try
		{
		   rs = getResultSet(query);
		   if(rs!=null)
		   {
			   rs.next ();
			   count = rs.getInt (key);
		   }
		}
		catch(SQLException e)
		{
			logger.error("Query '"+query+"' execution error: " + e.getMessage());
		}
		finally
		{
			closeRs(rs);
		}		
		
		return count;
	}	
	
	
	/**
	 * Returns the instances count
	 * @return count
	 */
	public Object getSingleValueByQuery(String query, String key)
	{
		logger.debug(query);
		Object value = null;
		if(dataAccess==null)
			return 0;
		
		ResultSet rs = null;
		try
		{
		   rs = getResultSet(query);
		   if(rs!=null && rs.next ())
			   value = rs.getObject(key);
		}
		catch(SQLException e)
		{
			logger.error("Query '"+query+"' execution error: " + e.getMessage());
		}
		finally
		{
			closeRs(rs);
		}
		
		return value;
	}		
	

	/**
	 * Returns the instances count
	 * @return count
	 */
	public List<String> getListOfStringsByQuery(String query, String key)
	{
		logger.debug(query);
		List<String> res = new ArrayList<String>();
		if(dataAccess==null)
			return null;
		
		ResultSet rs = null;
		try
		{
		   rs = getResultSet(query);
		   while(rs!=null && rs.next ())
			   res.add(rs.getString(key));
		}
		catch(SQLException e)
		{
			logger.error("Query '"+query+"' execution error: " + e.getMessage());
		}
		finally
		{
			closeRs(rs);
		}
		return res;
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
		
		ResultSet rs = null;
		try
		{
		   rs = getResultSet("SELECT count(1) as count FROM " + tableName);
		   if(rs!=null){
		   rs.next ();
		   count = rs.getInt ("count");
		   }
		}
		catch(SQLException e)
		{
			logger.error("Query execution error: " + e.getMessage());
		}
		finally
		{
			closeRs(rs);
		}
		
		return count;
	}
}
