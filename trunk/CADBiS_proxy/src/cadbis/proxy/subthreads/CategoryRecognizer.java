package cadbis.proxy.subthreads;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.zip.DataFormatException;

import cadbis.CADBiSThread;
import cadbis.proxy.Categorizer;
import cadbis.proxy.httpparser.ResponseHttpParser;
import cadbis.utils.IOUtils;
import cadbis.utils.StringUtils;

public class CategoryRecognizer extends CADBiSThread {
 	private ResponseHttpParser ResponseParser;
 	private StringBuffer fullResponse;
 	private String HttpHost = "";
 	
 	public CategoryRecognizer(ResponseHttpParser ResponseParser, StringBuffer fullResponse, String HttpHost) {
 	 	this.ResponseParser = ResponseParser;
 	 	this.fullResponse = fullResponse;
 	 	this.HttpHost = HttpHost;
	}
 	
	public void run()
	{	
		logger.debug("Category unknown, have to parse whole response... " );
		String body = fullResponse.toString();
		logger.info("Content charset = '"+ResponseParser.GetCharset()+"'");
		String charset = ResponseParser.GetCharset();
		if(!charset.toUpperCase().equals("UTF-8"))
		{
			try {
				body = new String(body.getBytes(ResponseParser.GetCharset()), "UTF-8");
			} catch (UnsupportedEncodingException e) {
				logger.error("Response contains unsupported encoding '"+charset+"':"+e.getMessage());
			}
		}
			try
			{					
				Integer headerEnd = body.indexOf("\r\n\r\n");
				if(headerEnd > -1)
				{
					logger.info("Splitting the header from body header = '"+body.substring(0, headerEnd)+"'");
					body = body.substring(headerEnd + 4);
					if(!ResponseParser.GetHeader("Content-Length").isEmpty()){
						Integer content_length = Integer.valueOf(ResponseParser.GetHeader("Content-Length"));
						if(body.length()>content_length && content_length!=0)
							body = body.substring(0,content_length);
					}
					else
					{
						Integer nullBytePos = body.indexOf(0);
						if(nullBytePos>0)
							body = body.substring(0,nullBytePos);
					}
				}
			if(ResponseParser.GetHeader("Content-Encoding").equals("gzip"))
				body = new String(StringUtils.getChars(IOUtils.UnGzipArray(body.getBytes())));
			}
			catch(DataFormatException e)
			{
				logger.error("Data format error while trying to unzip body of packet ");
			}
			catch(IOException e)
			{
				logger.error("IO error '"+e.getMessage()+"' while trying to unzip body of packet with header = "+ResponseParser.GetFullHeader());
			}				
		Categorizer.getInstance().recognizeAndAddCategory(HttpHost, body,charset);		
		complete();
	}
	

}
