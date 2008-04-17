package cadbis.proxy;

import java.io.BufferedOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.ArrayList;
import java.util.Date;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;



class ProxyConnection extends Thread {

	 private Socket fromClient;
	 private String host;
	 private int port;
	 private static Integer threadCount = 0;
	 private long timeout;
	 private final Logger logger = LoggerFactory.getLogger(getClass());

	 ProxyConnection(Socket s, String host, int port, long timeout) 
	 {
	  fromClient=s;
	  this.host = host;
	  this.port = port;
	  this.timeout=timeout;
		 synchronized (getClass()) {
				threadCount++;
				logger.info("ThreadCount=" + threadCount);
			 }	  	  
	 }

	 public void run() 
	 {
		 InputStream clientIn = null;
		 OutputStream clientOut = null;
		 InputStream serverIn = null;
		 OutputStream serverOut = null;
		 
	  
		 int cAvail=-1,sAvail=-1,chBuf=-1;
		 long startTime = new Date().getTime();
		 long endTime = new Date().getTime();
		 final HttpParser httpParser = new HttpParser();
		 Socket toServer = null;
		 
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
		 
		 
		 logger.debug("open connection to:"+toServer+"(timeout="+timeout+" ms)");		 
		 
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
			 long rcvdBytes = 0;
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
				 // creating the buffer for the readed data
				 ArrayList<byte[]> buffer = new ArrayList<byte[]>();
				 while((sAvail=serverIn.available())>0) 
				 {	
					 // we can recieve sAvail bytes
					 rcvdBytes += sAvail;
					 // indicate that we have read smthg
					 isReadWrite = true;
					 // indicate the last read time
					 startTime = new Date().getTime();
					 byte[] charBuf = new byte[sAvail];
					 serverIn.read(charBuf);
					 // adding the data to buffer
					 buffer.add(charBuf);				     
				 }
				 
				 
				 // if we have read smthg
				 if(buffer.size()>0)
				 {
					 logger.debug("buffer, blocks count = " + buffer.size());					 
					 for(int i=0;i<buffer.size();++i)
					 {
						 logger.debug("block["+i+"].size=" + buffer.get(i).length);
						 clientOut.write(buffer.get(i));
						 clientOut.flush();
					 }
					 
					 
					 // collecting (preCollector)
					 // bytes recieved
					 final long bytes = rcvdBytes;
					 // current user IP
					 final String userIp = fromClient.getInetAddress().getHostAddress();
					 // next proxy (squid) IP
					 final String toServerIp = toServer.getInetAddress().getHostAddress();
					 // creating the closure and the separate thread					 
					 new Thread(){
							public void run()
							{	
								Integer hostPort = 80;
								String hostName = httpParser.GetHeader("Host");
								String hostIp = toServerIp;
								if(hostName.indexOf(":")>0){
									String[] buf = hostName.split(":");
									if(buf.length > 1)
									{
										hostName = buf[0];
										hostPort = Integer.valueOf(buf[1]);
									}
								}
								
								try
								{
									Socket dnsQuery = new Socket(hostName,hostPort);
									hostIp = dnsQuery.getInetAddress().getHostAddress();
								}
								catch(IOException e)
								{
									logger.error("PreCollector fails to recognize the host's ip address: " + e.getMessage());
								}								
								Collector.getInstance().Collect(userIp, hostName, bytes, new Date(), hostIp);
							}
					 }.start();
					 
					 
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
			 logger.debug("Connections closed successfully...");
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