package cadbis.proxy;

import java.io.BufferedOutputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.Socket;
import java.net.UnknownHostException;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.zip.DataFormatException;
import java.util.zip.Inflater;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.CADBiSThread;
import cadbis.bl.Action;
import cadbis.utils.IOUtils;
import cadbis.utils.StringUtils;



class ProxyConnection extends CADBiSThread {

	 private Socket fromClient;
	 private String host;
	 private int port;
	 private long timeout;
	 private final Logger logger = LoggerFactory.getLogger(getClass());
	 private static final int MAX_BLOCK_SIZE = 4096;
	 private boolean trueproxy = false;
	 
	 
	 // lifetime members
	 private long startTime = new Date().getTime();
	 private long endTime = new Date().getTime();
	 private boolean isReadWrite = true;
	 private boolean isAccessDenied = false;
	 private int ErrorsCount = 0;
	 private String HttpHost = "";
	 private int HttpPort = 80;
	 private String HostIp = "";
	 private StringBuffer fullResponseBuffer = new StringBuffer();
	 private boolean isPossibleContentEnd = false;
	 private String UserIp;
	 
	 ProxyConnection(Socket s, long timeout)
	 {
		 fromClient=s;
		 trueproxy = true;
		 this.timeout=timeout;
	 }
	 
	 ProxyConnection(Socket s, String fwdhost, int fwdport, long timeout) 
	 {
		  fromClient=s;
		  this.host = fwdhost;
		  this.port = fwdport;
		  this.timeout=timeout;
	 }

	 protected boolean isNeedToCheckContent(HttpParser ResponseParser)
	 {
		 boolean enabled = ProxyConfigurator.getInstance().getProperty("contentcheck").equals("enabled");
		 return enabled && (ResponseParser.GetHeader("Content-Type").indexOf("text/html")>=0);
	 }
	 
	 protected void answerAccessDenied(HttpParser RequestParser,List<byte[]> buffer, String UserIp, String HttpHost)
	 {
			String accDenied = ProxyConfigurator.getInstance().getFile_denied_access();
			accDenied = accDenied.replace("%U",RequestParser.GetHeader("Host"));
			accDenied = accDenied.replace("%T",new Date().toString());
			for(int i=0;i<accDenied.length();i+=MAX_BLOCK_SIZE)
			{
				int sAvail = 0;
				if(i+MAX_BLOCK_SIZE<accDenied.length())
					sAvail = MAX_BLOCK_SIZE;
				else
					sAvail = accDenied.length() - sAvail - 1;												
				buffer.add(accDenied.substring(i,sAvail).getBytes());
			}
			 /*******************************
			  * log the attempt in a separate thread
			  *******************************/
			final String fHttpHost = HttpHost;
			final String fUserIp = UserIp;
			 new CADBiSThread(){
					public void run()
					{	
						Collector.getInstance().AddDeniedAccessAttempt(fUserIp, fHttpHost);
						complete();
					}
			 }.start();		 
	 }
	 
	 
	 protected Integer RecognizeCategory(HttpParser ResponseParser, StringBuffer fullResponse, String HttpHost)
	 {
			logger.info("Category unknown, have to parse whole response... " );
			//ByteBuffer fullResponse = ByteBuffer.allocate(totalRcvd);
			String body = "";
			if(ResponseParser.GetHeader("Content-Encoding").equals("gzip"))
			{
				try
				{
					String[] parts = fullResponse.toString().split("\r\n\r\n");
					if(parts.length>1)
					{						
						if(!ResponseParser.GetHeader("Content-Length").isEmpty()){
							Integer content_length = Integer.valueOf(ResponseParser.GetHeader("Content-Length"));
							body = parts[1].substring(0,content_length);
							logger.info("Body with header, splitted. header='"+parts[0]+"', bodylength="+body.length());							
							if(parts[1].length()!=content_length)
								logger.error("Parsing response error: "+body.length()+"!="+content_length);
						}
					}
					
					body = new String(StringUtils.getChars(IOUtils.UnzipArray(body.getBytes())));
				}
				catch(DataFormatException e)
				{
					logger.error("Data format error while trying to unzip body of packet with header = "+ResponseParser.GetFullHeader());
				}
				catch(IOException e)
				{
					logger.error("IO error while trying to unzip body of packet with header = "+ResponseParser.GetFullHeader());
				}
			}
			else
				body = fullResponse.toString();
			logger.info("Parsed, body length="+body.length()+"...");
			return Categorizer.getInstance().recognizeAndAddCategory(HttpHost, body);

	 }
	 

	 public void run() 
	 {
		 InputStream clientIn = null;
		 OutputStream clientOut = null;
		 InputStream serverIn = null;
		 OutputStream serverOut = null;
		 
	  
		 startTime = new Date().getTime();
		 endTime = new Date().getTime();
		 Socket toServer = null;
		 
		 // defining the streams
		 try
		 {			 
			 clientIn = fromClient.getInputStream();
			 clientOut = new BufferedOutputStream(fromClient.getOutputStream());
		 }
		 catch(IOException e)
		 {
			 logger.error(e.getMessage());
		 }

		 isReadWrite = true;
		 isAccessDenied = false;
	 	 ErrorsCount = 0;
		 HttpHost = "";
		 HttpPort = 80;
		 HostIp = "";
		 fullResponseBuffer = new StringBuffer();
		 isPossibleContentEnd = false;
		 
		 // current user IP
		 UserIp = fromClient.getInetAddress().getHostAddress();
		 final int WaitRWPeriod = Integer.parseInt(ProxyConfigurator.getInstance().getProperty("waitrwtime"));
		 final int MaxErrorsCount = Integer.parseInt(ProxyConfigurator.getInstance().getProperty("maxerrorscount"));
		 final HttpParser 
			RequestParser= new HttpParser(), 
			ResponseParser= new HttpParser();
		 
		 while(endTime - startTime < timeout && ErrorsCount<MaxErrorsCount) 
		 {
			 //logger.debug("new packet processing iteration, timeout: " + (endTime-startTime) +" ms");
			 
			 /**
			  * guess that content is already fully recieved if timeout
			  * is reached in half
			  */
			 isPossibleContentEnd = (endTime - startTime >= 3*timeout/4);
			 
			 
			 isReadWrite = false;			 
			 String cRcvdData = new String("");
			 int RcvdAmount = 0;
			 List<byte[]> buffer = new ArrayList<byte[]>();		
	 
			 
			 /*******************************
			  * Recieving data client->proxy
			  *******************************/			 
			 try{
				buffer.clear();
				IOUtils.readStreamAsArray(clientIn, buffer);
				if(buffer.size()>0)
					logger.debug("read from clientIn completed " + buffer.size()+" blocks read");	
			 }
			 catch(IOException e)
			 {
				 ErrorsCount++;
				 logger.error("Recieving data client->proxy error: "+e.getMessage());
			 }
			 
			 
			 /*******************************
			  * Parsing request
			  *******************************/	
			 if(buffer.size()>0)
			 {
				cRcvdData = new String(StringUtils.getChars(buffer.get(0)));
				startTime = new Date().getTime();
				isReadWrite = true;
				if(!RequestParser.isRequestParsed()){
					RequestParser.ClearHeaders();
					RequestParser.ParseRequestHeaders(cRcvdData);
				}
				//buffer.set(0, RequestParser.GetFixedFullHeader().getBytes());
				buffer.set(0, RequestParser.GetFixedPacket(buffer.get(0)));
				HttpHost = RequestParser.getHttpHost();
				HttpPort = RequestParser.getHttpPort();		
			 }

			 
			 
			 /*******************************
			  * Connecting proxy->squid
			  *******************************/
			 if(toServer == null && buffer.size()>0)
			 {				 
				 HostIp = "";
				 String hostTo=this.host;
				 int portTo=this.port;
				 if(trueproxy)
				 {
					 hostTo = HttpHost;
					 portTo = HttpPort;
					 logger.debug("True proxying enabled: client->"+hostTo+":"+portTo);
				 }
				 try
				 {
					 logger.debug("Opening connection to server " + hostTo + ":" + portTo);
					 toServer = new Socket(hostTo,portTo);
					 if(trueproxy){
						 HostIp = toServer.getInetAddress().getHostAddress();						 
					 }
					 serverIn = toServer.getInputStream();
					 serverOut = new BufferedOutputStream(toServer.getOutputStream());
				 }
				 catch(UnknownHostException e)
				 {
					 ErrorsCount++;
					 logger.error("Connecting proxy->squid("+hostTo+":"+portTo+") error: unknown host: " + e.getMessage());
				 }		 
				 catch(IOException e)
				 {
					 ErrorsCount++;
					 logger.error("Connecting proxy->squid("+hostTo+":"+portTo+") error: "+e.getMessage());
				 }
			 }
			 
			 
			 
			/*******************************
			 * Sending data proxy->squid
			 ******************************/
			 try{
				if(toServer!=null && buffer.size()>0)
				{
					// check if url is denied
					isAccessDenied = !Collector.getInstance().CheckAccessToUrl(UserIp,HttpHost);
					if(!isAccessDenied)
					{
						//cRcvdData = RequestParser.GetFixedFullHeader();
						logger.debug("writing to serverOut "+buffer.size()+" blocks...");
						IOUtils.writeArrayToStream(serverOut, buffer);
						logger.debug("write to serverOut completed...");
						startTime = new Date().getTime();
						isReadWrite = true;
					}
				}
				
			 }
			 catch(IOException e)
			 {
				 ErrorsCount++;
				 logger.error("Sending data proxy->squid("+toServer.getInetAddress().getHostName()+":"+toServer.getPort()+") error: " + e.getMessage());
			 }			 
			 
			 
			 
			 /*******************************
			  * Receiving data squid->proxy
			  *******************************/	 
			 try
			 {
				buffer.clear();
				if(!isAccessDenied && toServer!=null)
					RcvdAmount = IOUtils.readStreamAsArray(serverIn, buffer);
				else if(toServer!=null)
				{
					isAccessDenied = false;
					answerAccessDenied(RequestParser,buffer,UserIp,HttpHost);
				}
			 }
			 catch(IOException e)
			 {
				 ErrorsCount++;
				 logger.error("Receiving data  squid("+toServer.getInetAddress().getHostName()+":"+toServer.getPort()+")->proxy error: " + e.getMessage());
			 }
								
			 
			 /**************************************
			  * Parse the response headers
			  *************************************/
			 if(buffer.size()>0)
				 if(!ResponseParser.isResponseParsed())
				 {
					 logger.debug("This is a first time of response processing, parse headers...");
					 ResponseParser.ParseResponseHeaders(new String(StringUtils.getChars(buffer.get(0))));
				 }
				 else
					 logger.debug("This is not a first time of response processing, skip headers...");
			 
			 /***************************************
			  * Analyzing the response to define the access
			  * to category
			  * TODO: The place for content analyze
			  * analyze only text/html content
			  ***************************************/
			 if(isNeedToCheckContent(ResponseParser))
			 {
				 Integer cid = Categorizer.getInstance().getCategoryForUrl(HttpHost);
				 // category not recognized, trying to recognize it
				 if(cid == null)
				 {				 
					 /**
					  * filling the full response buffer only if 
					  * the category is not recognized...
					  */
					 for(int i=0;i<buffer.size();++i)
						 fullResponseBuffer.append(StringUtils.getChars(buffer.get(i)));
					 
					 /**
					  * we must do it only if the content 
					  * is already fully received 
					  */							 
					 if(isPossibleContentEnd)
					 {
						logger.info("Possible content end reached, trying to recognize category...");
						cid = RecognizeCategory(ResponseParser, fullResponseBuffer, HttpHost);
					 }
				 }
				 else
				 {
					 Action act = Collector.getInstance().getActionByUserIp(UserIp);
					 logger.debug("Checking access of "+act.getUser()+" to cid="+cid+"...");
					 if(!new ContentAccessChecker().checkAccessOfUserToCategory(act.getUser(), cid))
						{
							isAccessDenied = false;
							answerAccessDenied(RequestParser,buffer,UserIp,HttpHost);
						}
				 }
				 
			 }			 
			 
			 
			/*******************************
			 * Sending proxy->client
			 *******************************/	
			 try
			 {
				 // if we have read smthg
				 if(buffer.size()>0 && toServer!=null)
				 {
					 logger.debug("Response.type = "+ResponseParser.GetHeader("Content-Type"));
					 logger.debug("buffer, blocks count = " + buffer.size());					 
					 isReadWrite = true;
					 // indicate the last rw time
					 startTime = new Date().getTime();				 
					 for(int i=0;i<buffer.size();++i)
					 {
						 logger.debug("block["+i+"].size=" + buffer.get(i).length);
						 clientOut.write(buffer.get(i));
						 clientOut.flush();
					 }
					 buffer.clear();
					 /*******************************
					  * Sending the data to collector in a separate thread
					  *******************************/	
					 String fHttpHost = HttpHost;
					 String ContentType = ResponseParser.GetHeader("Content-Type");
					 new PreCollector(fHttpHost,RcvdAmount,UserIp,ContentType,HostIp)
					 	.start();
				 }
			 }
			 catch(IOException e)
			 {
				 ErrorsCount++;
				 logger.error("Sending proxy->client("+fromClient.getInetAddress().getHostName()+":"+fromClient.getPort()+") error: " + e.getMessage());
			 }
			

			 
			 try
			 {
				 if(!isReadWrite) 
				 {
					 endTime = new Date().getTime();
					 Thread.sleep(WaitRWPeriod);
				 }				 
			 }
			 catch(InterruptedException e)
			 {
				 ErrorsCount++;
				 logger.error("Processing error: " + e.getMessage());
			 }
		 }
			 
		 // closing connections
		 try 
		 {
			 if(clientIn!=null)
				 clientIn.close();
			 if(clientOut!=null)
				 clientOut.close();
			 if(fromClient!=null)
				 fromClient.close();
			 if(serverIn!=null)
				 serverIn.close();
			 if(serverOut!=null)
				 serverOut.close();
			 if(toServer!=null)
				 toServer.close();
			 logger.debug("Connections closed successfully...");
		 }
		 catch(Exception e) 
		 {
			 logger.error("Warning! Connections close error!");
			 e.printStackTrace(System.err);
		 }
		 finally
		 {			 
			 // complete our thread
			 complete();
		 }
	}
	 
	 @Override
	protected void finalize() throws Throwable {
		logger.debug("ProxyConnection thread garbage collected");
		super.finalize();
		
	}
}