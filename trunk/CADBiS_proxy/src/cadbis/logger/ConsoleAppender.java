package cadbis.logger;

import java.text.SimpleDateFormat;
import java.util.Date;

import org.apache.log4j.spi.LoggingEvent;

public class ConsoleAppender extends org.apache.log4j.ConsoleAppender {
	@Override
	public synchronized void doAppend(LoggingEvent event) {
		String message = "";
		message += new SimpleDateFormat("HH:mm:ss").format(new Date());
		message += " ["+event.getLoggerName()+"]";
		message += "\t ["+event.getThreadName()+"]";
		message += "\t mem: "+((Runtime.getRuntime().totalMemory()-Runtime.getRuntime().freeMemory())/1024)+"/"+(Runtime.getRuntime().totalMemory()/1024)+"/"+(Runtime.getRuntime().maxMemory()/1024)+"Kb";
		message += "\t " + event.getMessage();
		System.out.println(message);
	}
}
