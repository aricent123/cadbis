package cadbis.proxy.test;

import java.io.BufferedOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.Date;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;



class ProxyConnection extends Thread {

	 private Socket fromClient;
	 private String host;
	 private int port;
	 private long timeout;
	 private final Logger logger = LoggerFactory.getLogger(getClass());

	 ProxyConnection(Socket s, String host, int port, long timeout) 
	 {
	  fromClient=s;
	  this.host = host;
	  this.port = port;
	  this.timeout=timeout;
	 }

	 public void run() 
	 {
		 InputStream clientIn = null;
		 OutputStream clientOut = null;
		 InputStream serverIn = null;
		 OutputStream serverOut = null;
		 Socket toServer = null;
	  
		 int cAvail=-1,sAvail=-1,chBuf=-1;
		 long startTime = new Date().getTime();
		 long endTime = new Date().getTime();
		 
		 
		 
		 // opening the connection to server
		 try
		 {
			 toServer = new Socket(host,port);
		 }
		 catch(UnknownHostException e)
		 {
			 logger.error("Unknown host: " + e.getMessage());
		 }
		 catch(IOException e)
		 {
			 logger.error(e.getMessage());
		 }
		 
		 
		 logger.info("open connection to:"+toServer+"(timeout="+timeout+" ms)");		 
		 
		 // defining the streams
		 try
		 {			 
			 clientIn = fromClient.getInputStream();
			 clientOut = new BufferedOutputStream(fromClient.getOutputStream());
			 serverIn = toServer.getInputStream();
			 serverOut = new BufferedOutputStream(toServer.getOutputStream());
		 }
		 catch(IOException e)
		 {
			 logger.error(e.getMessage());
		 }

		 while((cAvail!=0 || sAvail!=0) || ((endTime - startTime) <= timeout)) 
		 {
			 // trying to recieve data from client
			 try{				 		
				 // while available some data
				 while((cAvail=clientIn.available())>0) 
				 {
				     for(int i=0; i<cAvail; i++) 
				     {
				    	 chBuf = clientIn.read();
				    	 if(chBuf!=-1) 
				    		 serverOut.write(chBuf);
				     }
				     startTime = new Date().getTime();
				     serverOut.flush();
			    }				 
			 }
			 catch(IOException e)
			 {
				 logger.error(e.getMessage());
			 }
			 
			 
			 
			 // trying to send data to server
			 try{
				 while((sAvail=serverIn.available())>0) 
				 {
				     for(int i=0; i<sAvail; i++)
				     {
				    	 chBuf = serverIn.read();
				    	 if(chBuf!=-1) 
				    		 clientOut.write(chBuf);
				     }
				     
				     clientOut.flush();
				 }
			 }
			 catch(IOException e)
			 {
				 logger.error(e.getMessage());
			 }
			
			
			 try
			 {
				 if(sAvail==0 && cAvail==0) 
				 {
					 endTime = new Date().getTime();
					 Thread.sleep(100);
					 logger.info("waiting:"+(endTime-startTime)+" ms");
				 }				 
			 }
			 catch(InterruptedException e)
			 {
				 logger.error(e.getMessage());
			 }		     
		 }
			 
		 // closing connections
		 try 
		 {
			 clientIn.close();
			 clientOut.close();
			 serverIn.close();
			 serverOut.close();
			 fromClient.close();
			 toServer.close();
		 } 
		 catch(Exception e) 
		 {
			 e.printStackTrace(System.err);
		 }
	}
}