package cadbis.proxy.db;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.proxy.Configurator;

public class DBConnection {
	private final Logger logger = LoggerFactory.getLogger(getClass());
	
	private Connection connection = null;
    private String userName;
    private String password;
    private String jdbcUrl;
    private String jdbcDriver;    
    
    private static DBConnection instance = null;
	
	private DBConnection()
	{
		this.userName = Configurator.getInstance().getProperty("username");
		this.password = Configurator.getInstance().getProperty("password");
		this.jdbcUrl = Configurator.getInstance().getProperty("jdbcurl");
		this.jdbcDriver = Configurator.getInstance().getProperty("jdbcdriver");
		Connect();
	}
	
	public static DBConnection getInstance()
	{
		if(DBConnection.instance == null)
			DBConnection.instance = new DBConnection();
		return DBConnection.instance;
	}
	
	public boolean Connect()
	{
		boolean result = false; 
        try
        {
            Class.forName (jdbcDriver).newInstance ();
            connection = DriverManager.getConnection (jdbcUrl, userName, password);
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
        return result;
	}

	
	public boolean Reconnect()
	{
		boolean res = false;
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
		return res;
	}

	public void Disconnect()
	{
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
