package cadbis.db;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Properties;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.proxy.ProxyConfigurator;

public class DBConnection {
	private final Logger logger = LoggerFactory.getLogger(getClass());
	
	private Connection connection = null;
    private String userName;
    private String password;
    private String jdbcUrl;
    private String jdbcDriver;    
    
    private static Object dcLock = new Object();
    private static DBConnection instance = null;
	
	private DBConnection()
	{
		this.userName = ProxyConfigurator.getInstance().getProperty("username");
		this.password = ProxyConfigurator.getInstance().getProperty("password");
		this.jdbcUrl = ProxyConfigurator.getInstance().getProperty("jdbcurl");
		this.jdbcDriver = ProxyConfigurator.getInstance().getProperty("jdbcdriver");
		Connect();
	}
	
	public static DBConnection getInstance()
	{
		synchronized (dcLock) {
		if(DBConnection.instance == null)
			DBConnection.instance = new DBConnection();
		}
		return DBConnection.instance;
	}
	
	public boolean Connect()
	{
		boolean result = false; 
		synchronized (dcLock) {		
	        try
	        {
	            Class.forName (jdbcDriver).newInstance ();
	            Properties connInfo = new Properties();
	            connInfo.put("user",userName);
	            connInfo.put("password",password);
	            connInfo.put("useUnicode","true");
	            connInfo.put("characterEncoding","UTF-8");
	            connection = DriverManager.getConnection(jdbcUrl, connInfo);
	            
	            logger.info("Database connected: " + jdbcUrl);
	            result = true;
	        }
	        catch (SQLException e)
	        {
	        	logger.error("Cannot connect to database server " + e.getMessage());
	        }        
	        catch(ClassNotFoundException e)
	        {
	        	logger.error("Cannot connect to database server. Class not found: " + e.getMessage());
	        }
	        catch(Exception e)
	        {
	        	logger.error("Unknown error while connecting to database server. " + e.getMessage());
	        }
		}
        return result;
	}

	
	public boolean Reconnect()
	{
		boolean res = false;
		synchronized (dcLock) {	
			try{
				if(connection != null)
					connection.close();
				connection = null;
				res = Connect();
			}
			catch (SQLException e) {
				logger.error("Reconnect failed: " + e.getMessage());
				return false;
			}
		}
		return res;
	}

	public void Disconnect()
	{
		synchronized (dcLock) {			
            if (connection != null)
            {
                try
                {
                	connection.close ();
                	connection = null;
                	instance = null;
                	logger.info("Database connection terminated");
                }
                catch (Exception e) { /* ignore close errors */ }
            }
		}
	}
	
	
	@Override
	protected void finalize() throws Throwable {
		this.Disconnect();
		super.finalize();
	}

	public Connection getConnection() {
		return connection;
	}

	public void setConnection(Connection connection) {
		this.connection = connection;
	}

	public String getUserName() {
		return userName;
	}

	public void setUserName(String userName) {
		this.userName = userName;
	}

	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	public String getJdbcUrl() {
		return jdbcUrl;
	}

	public void setJdbcUrl(String jdbcUrl) {
		this.jdbcUrl = jdbcUrl;
	}

	public String getJdbcDriver() {
		return jdbcDriver;
	}

	public void setJdbcDriver(String jdbcDriver) {
		this.jdbcDriver = jdbcDriver;
	}
		
}
