package cadbis.proxy;

import java.net.*;
import java.util.List;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.proxy.bl.User;
import cadbis.proxy.db.UserDAO;

import sun.awt.windows.ThemeReader;

public class Proxy {

	public static final String usageArgs =" <localport> <host> <port> <timeout_ms>";
	private final Logger logger = LoggerFactory.getLogger(getClass());
	static int clientCount;
	private Integer threadCount=0;

public void run(int localport, String host, int port,long timeout) {
	try 
	{		
		ServerSocket sSocket = new ServerSocket(localport);
		while(true) 
		{
			logger.info("listening to " + String.valueOf(localport)+"...");
			Socket cSocket=null;
			try 
			{
				cSocket = sSocket.accept();				
				if(cSocket!=null) 
				{
					logger.info("accepted as #"+clientCount+":"+cSocket);
					logger.info("Total threads count=" + threadCount);
					clientCount++;
					ProxyConnection c = new ProxyConnection(cSocket,host,port,timeout,threadCount);
					c.start();
				}
			} 
			catch(Exception e) 
			{
				e.printStackTrace(System.err);
			}    
		}
	} 
	catch(Throwable t) 
	{
		t.printStackTrace(System.err);
	}
}

	public static void main(String[] argv) 
	{	
		UserDAO userDAO = new UserDAO();
		userDAO.getCount();
		List<User> users = userDAO.getItemsByQuery("select * from users");
		for(int i=0; i<users.size();++i)
			System.out.println("user="+users.get(i).getUser());
		
		Proxy self = new Proxy();
		if(argv.length>=3) 
		{
			int localport = Integer.parseInt(argv[0]);
			String url = argv[1];
			int port = Integer.parseInt(argv[2]);
			int timeout = Integer.parseInt(argv[3]);
			self.run(localport,url,port,timeout);	 
		} 
		else 
		{
			System.err.println("usage: java " + self.getClass().getName() + usageArgs);
		}
	}
}
