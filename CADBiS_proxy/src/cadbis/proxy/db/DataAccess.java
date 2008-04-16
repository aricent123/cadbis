package cadbis.proxy.db;

import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Properties;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class DataAccess {
	private final String FILE_DB_PROPERTIES = "database.properties";
	private final Logger logger = LoggerFactory.getLogger(getClass());
	
	private Connection connection = null;
    private String userName;
    private String password;
    private String jdbcUrl;
    private String jdbcDriver;    
    
    private static DataAccess instance = null;
	
	private DataAccess()
	{
	    Properties properties = new Properties();
	    try {
	    	properties.load(Thread.currentThread().getContextClassLoader().getResourceAsStream(FILE_DB_PROPERTIES));
		    this.userName = properties.getProperty("userName");
		    this.password = properties.getProperty("password");
		    this.jdbcUrl = properties.getProperty("jdbcUrl");
		    this.jdbcDriver = properties.getProperty("jdbcDriver");
		    Connect();
	    } 
	    catch (IOException e) 
	    {
	    	logger.error("Could not read database.properties file: " + e.getMessage());
	    }
	}
	
	public static DataAccess getInstance()
	{
		if(instance == null)
			instance = new DataAccess();
		return instance;
	}
	
	public boolean Connect()
	{
		boolean result = false; 
        try
        {
            Class.forName (jdbcDriver).newInstance ();
            connection = DriverManager.getConnection (jdbcUrl, userName, password);
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


	public void Disconnect()
	{
            if (connection != null)
            {
                try
                {
                	connection.close ();
                	connection = null;
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
