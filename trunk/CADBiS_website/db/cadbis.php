#!/usr/local/bin/php
<?

class cmd{
	const IPFW_PROXY_ADD = 'ipfw add 320 fwd 127.0.0.1,8888 tcp from 10.0.0.0/8 to any 80';
	const IPFW_PROXY_DELETE  = 'ipfw delete 320';
	const IPFW_OPEN_ALL  = 'ipfw add 1220 allow ip from 192.168.40.0/24 to any && ipfw add 1220 allow ip from any to 192.168.40.0/24';
	const IPFW_CLOSE_ALL  = 'ipfw delete 1220';
	const SQUID_START = 'squid  > /dev/null &';
	const SQUID_STOP = 'squid -k shutdown > /dev/null &';
	const MPD_START = 'mpd4 -b &';
	const MPD_STOP = 'mpd4 --kill | killall mpd4 > /dev/null &';
	const CADBIS_START = 'java -jar -server /home/smecsia/cadbis/daemon/cadbis.jar /home/smecsia/cadbis/daemon/conf/jradius-config.xml > /home/smecsia/cadbis/daemon/cadbis.log &';
	const CADBIS_STOP = 'killall java > /dev/null &';	
	const FREERADIUS_START = 'radiusd &';
	const FREERADIUS_STOP = 'killall radiusd > /dev/null &';
	const CADBIS_STATUS = array(array('cmd'=>'sockstat | grep java','ok'=>array('127.0.0.1:8888','127.0.0.1:1814'),'fail'=>array('')));
	const MPD_STATUS = array(array('cmd'=>'sockstat | grep mpd4','ok'=>array('127.0.0.1:5005'),'fail'=>array('')));
	const FREERADIUS_STATUS = array(array('cmd'=>'ps ax | grep radiusd','ok'=>array(' radiusd'),'fail'=>array('')));
	const SQUID_STATUS = array(array('cmd'=>'sockstat | grep squid','ok'=>array('127.0.0.1:3128'),'fail'=>array('')));	
	
}

class proxy{
	public static function start(){
		shell::exec(cmd::IPFW_PROXY_DELETE);
		shell::exec(cmd::IPFW_PROXY_ADD);				
	}
	
	public static function stop(){
		shell::exec(cmd::IPFW_PROXY_DELETE);
	}
}

class cadbis{
	public static function start(){
		self::stop();
		shell::exec(cmd::MPD_START);		
		shell::exec(cmd::SQUID_START);
		shell::exec(cmd::CADBIS_START);
		shell::exec(cmd::FREERADIUS_START);
		
	}
	public static function stop(){
		shell::exec(cmd::MPD_STOP);		
		shell::exec(cmd::SQUID_STOP);
		shell::exec(cmd::CADBIS_STOP);
		shell::exec(cmd::FREERADIUS_STOP);		
	}
	
	
	public static function close(){
		shell::exec(cmd::IPFW_CLOSE_ALL);
	}	
	
	public static function open(){
		shell::exec(cmd::IPFW_OPEN_ALL);
	}
	
	protected static function test_app($app){
		foreach($app as $el)
			self::test_app($el);
		
	}
	
	public static function status(){
		
	}
}


class shell{	
	protected function quit(){die("\r\n");}
	protected function print_usage()
	{
		echo("Usage: cadbis [option] start | stop | open | close \r\n option can have the following values: proxy");
	}
	protected function usage()
	{
		$this->print_usage();
		$this->quit();		
	}
	public static function exec($cmd)
	{
		exec($cmd);
	}
	
	public static function passthru($cmd)
	{
		return passthru($cmd);
	}

	protected function proceed_proxy($argv, $argc)
	{
		if($argc<3)
			$this->usage();
		switch($argv[2]){
			case 'start':
				proxy::start();
			break;
			case 'stop':
				proxy::stop();
			break;
			default:
				$this->usage();
			break;
		}
	}
	
	public function __construct($argv, $argc)
	{	
		if($argc<2)
			$this->usage();
		switch($argv[1]){
			case 'proxy':
				$this->proceed_proxy($argv, $argc);
			break;
			case 'start':
				echo "please wait while CADBiS starting...\r\n";
				cadbis::start();
			break;
			case 'stop':
				echo "please wait while CADBiS stopping...\r\n";
				cadbis::stop();
			break;
			case 'open':
				echo "Warning! Now Internet is open for whole LAN! ...\r\n";
				cadbis::open();
			break;	
			case 'close':
				cadbis::close();
			break;	
			default:
				$this->usage();				
		}
	}
};

new shell($_SERVER['argv'], $_SERVER['argc']);