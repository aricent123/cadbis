package cadbis.proxy.db;

import java.io.FileInputStream;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.util.Properties;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class DataAccess {
	private Connection connection = null;
    private String userName = "testuser";
    private String password = "testpass";
    private String jdbcUrl = "jdbc:mysql://localhost/test";
    private String jdbcDriver = "com.mysql.jdbc.Driver";
    private final Logger logger = LoggerFactory.getLogger(getClass());
	
	private DataAccess()
	{
	    Properties properties = new Properties();
	    try {
	        properties.load(new FileInputStream("database.properties"));
		    this.userName = properties.getProperty("userName");
		    this.password = properties.getProperty("password");
		    this.jdbcUrl = properties.getProperty("jdbcUrl");
		    this.jdbcDriver = properties.getProperty("jdbcDriver");	        
	    } 
	    catch (IOException e) 
	    {
	    	logger.error("Could not read database.properties file: " + e.getMessage());
	    }
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
        catch (Exception e)
        {
        	logger.error("Cannot connect to database server " + e.getMessage());
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
	
	
	
	
}
