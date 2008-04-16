package cadbis.proxy;

import java.io.BufferedOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.proxy.utils.StringUtils;



class ProxyConnection extends Thread {

	 private Socket fromClient;
	 private String host;
	 private int port;
	 private Integer threadCount=0;
	 private Object mutex;
	 private long timeout;
	 private final Logger logger = LoggerFactory.getLogger(getClass());
	 private final int MAX_ITERATIONS = 10;

	 ProxyConnection(Socket s, String host, int port, long timeout, Integer threadCount) 
	 {
	  fromClient=s;
	  this.host = host;
	  this.port = port;
	  this.timeout=timeout;
	  this.mutex = mutex;
	  this.threadCount = threadCount;	  
		 synchronized (getClass()) {
				this.threadCount++;
				logger.info("ThreadCount=" + threadCount);
			 }	  	  
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
		 HttpParser httpParser = new HttpParser();
		 
		 
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

		 
		 
		 boolean isReadWrite = true;
		 while(isReadWrite || endTime - startTime < timeout) 
		 {
			 isReadWrite = false;
			 // trying to recieve data from client
			 try{
				 String cRcvdData = "";
				 // while available some data
				 while((cAvail=clientIn.available())>0) 
				 {
					 isReadWrite = true;
				     for(int i=0; i<cAvail; i++) 
				     {
				    	 chBuf = clientIn.read();
				    	 if(chBuf!=-1) 
				    		 cRcvdData += (char)chBuf;
				     }
				     startTime = new Date().getTime();				     
			    }	
				
				// parsing and fixing headers
				if(!cRcvdData.equals(""))
				{
					httpParser.ClearHeaders();
					httpParser.ParseHeaders(cRcvdData);
					cRcvdData = httpParser.GetFixedFullRequestHeader();
					byte[] RcvdBytes = cRcvdData.getBytes();
					for(int i=0;i<RcvdBytes.length;++i)
						serverOut.write(RcvdBytes[i]);
					serverOut.flush();
				}
				
			 }
			 catch(IOException e)
			 {
				 logger.error(e.getMessage());
			 }
			 
			 
			 // trying to send data to server
			 try{
				 ArrayList<byte[]> buffer = new ArrayList<byte[]>();
				 int sAvailCounter = 0;
				 while((sAvail=serverIn.available())>0) 
				 {	
					 isReadWrite = true;
					 startTime = new Date().getTime();
					 byte[] charBuf = new byte[sAvail];
					 serverIn.read(charBuf);
					 buffer.add(charBuf);				     
				 }
				 
				 if(buffer.size()>0)
				 {
					 //logger.info("buffer, blocks count = " + buffer.size());
					 
					 for(int i=0;i<buffer.size();++i)
					 {
						 //logger.info("block["+i+"].size=" + buffer.get(i).length);
						 clientOut.write(buffer.get(i));
						 clientOut.flush();
					 }
				 }
				
			 }
			 catch(IOException e)
			 {
				 logger.error(e.getMessage());
			 }
			

			 
			 try
			 {
				 if(!isReadWrite) 
				 {
					 endTime = new Date().getTime();
					 Thread.sleep(100);
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
			 logger.info("Connections closed successfully...");
		 } 
		 catch(Exception e) 
		 {
			 e.printStackTrace(System.err);
		 }
		 finally
		 {
			 
			 synchronized (getClass()) {
				threadCount--;
				logger.info("ThreadCount=" + threadCount);
			 }
			 
		 }
	}
}