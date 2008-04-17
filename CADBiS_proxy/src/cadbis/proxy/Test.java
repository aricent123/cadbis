package cadbis.proxy;

import java.text.SimpleDateFormat;
import java.util.Date;

final class TestThread extends Thread
{
	private static Integer var=0;
	private static Integer ThreadsCount =0;
	private static Object testObj = new Object();
	@SuppressWarnings("static-access")
	@Override
	public void run() {		
		synchronized (testObj) {
			ThreadsCount++;
		}
		System.out.println("Before synchronized... thread"+ThreadsCount+", var="+var);
		synchronized (getClass()) {
			System.out.println("Inside synchronized... var="+var);
			var++;
			try{
				Thread.currentThread().sleep(100);
			}
			catch (InterruptedException e) {
				System.out.println("Thread interrupted: " + e.getMessage());
			}			
			
		}		
	}
}

public class Test {

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		
		System.out.print(new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date()));
		TestThread[] pool;
		pool = new TestThread[20];
		for(int i=0;i<pool.length;++i)
		{
			pool[i] = new TestThread();
			pool[i].start();
		}
		
		try{
			for(int i=0;i<pool.length;++i)
				pool[i].join();
		}
		catch(InterruptedException e)
		{
			System.out.println("Thread interrupted: " + e.getMessage());
		}
		
	}

}
