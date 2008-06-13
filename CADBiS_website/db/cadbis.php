#!/usr/local/bin/php
<?

define('MAX_WAIT_TIMES',10);
define('WAIT_RADIUS_PERIOD',10);
define('WAIT_BETWEEN_EXECS',5);

class stest{
	public $cmd;
	public $ok;
	public function __construct($cmd, $ok){
		$this->cmd = $cmd;
		$this->ok = $ok;
	}
}

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
	const FREERADIUS_START = 'radiusd -XA > /dev/null &';
	const FREERADIUS_STOP = 'killall radiusd > /dev/null &';
	public static $CADBIS_STATUS;
	public static $PROXY_STATUS;
	public static $MPD_STATUS;
	public static $FREERADIUS_STATUS;
	public static $SQUID_STATUS;
	
	public static function init(){
		self::$PROXY_STATUS = array(
						new stest('ipfw show | grep 320',array('fwd 127.0.0.1,8888 tcp from 10.0.0.0/8 to any dst-port 80')),
						new stest('sockstat | grep java',array('127.0.0.1:8888'))
					);
		self::$CADBIS_STATUS = array(
						new stest('sockstat | grep java',array('127.0.0.1:8888','127.0.0.1:1814'))
					);
		self::$MPD_STATUS = array(
						new stest('sockstat | grep mpd4',array('127.0.0.1:5005','192.168.40.4:1723'))
					);
		self::$FREERADIUS_STATUS = array(
						new stest('sockstat | grep radiusd',array('*:1812','*:1813','root     radiusd'))
					);
		self::$SQUID_STATUS = array(
						new stest('sockstat | grep squid',array('127.0.0.1:3128'))
					);
	}
}


class io{
	
	public static function exec($cmd)
	{
		return exec($cmd);
	}
	
	public static function shell_exec($cmd)
	{
		return shell_exec($cmd);
	}
	
	public static function checkstatus($tests){
		$isok = true;
		foreach($tests as $test){
			$res = io::shell_exec($test->cmd);			
			foreach($test->ok as $ok){
				$isok = $isok && (strstr($res, $ok));
			}
		}
		return $isok;		
	}
	public static function stat_str($res){
		return ($res)?'RUNNED':'STOPPED';
	}
	
}

class proxy{
	public static function start(){		
		if(io::checkstatus(cmd::$PROXY_STATUS))
			self::stop();
		if(!io::checkstatus(cmd::$PROXY_STATUS))
			io::exec(cmd::IPFW_PROXY_ADD);	
	}
	
	public static function stop(){		
			io::exec(cmd::IPFW_PROXY_DELETE);
	}
	public static function restart(){		
			self::stop();
			self::start();
	}
	
	public static function status(){
		echo("CADBiS proxy status : [".io::stat_str(io::checkstatus(cmd::$PROXY_STATUS))."]\r\n");
	}	
	
}

class cadbis{
	
	protected static function show_status($app, $cmd)
	{
		echo("$app status : [".io::stat_str(io::checkstatus($cmd))."]\r\n");
	}
	
	
	protected static function start_app($app, $cmd_status, $cmd_start)
	{
		if(!io::checkstatus($cmd_status)){
			echo("$app starting... \r\n");
			io::exec($cmd_start);	
			$wait_times = 0;		
			sleep(WAIT_BETWEEN_EXECS);
			while($wait_times++ < MAX_WAIT_TIMES && !io::checkstatus($cmd_status)){
				echo("Waiting for $app to start...\r\n");
				sleep(WAIT_BETWEEN_EXECS);
			}
			self::show_status("$app",$cmd_status);
		}		
	}	
	
	public static function start(){
		self::start_app("MPD",cmd::$MPD_STATUS,cmd::MPD_START);
		self::start_app("Squid",cmd::$SQUID_STATUS,cmd::SQUID_START);
		self::start_app("JRadius",cmd::$CADBIS_STATUS,cmd::CADBIS_START);
		sleep(WAIT_RADIUS_PERIOD);
		if(io::checkstatus(cmd::$CADBIS_STATUS))
			self::start_app("FreeRADIUS",cmd::$FREERADIUS_STATUS,cmd::FREERADIUS_START);
		else
			echo "Startup error! JRadius is not started, ask SM for some help!\r\n";		
	}
	
	public static function restart(){
		self::stop();
		self::start();
	}	
	
	
	protected static function stop_app($app, $cmd_status, $cmd_stop)
	{
		if(io::checkstatus($cmd_status)){
			echo("$app stopping... \r\n");
			io::exec($cmd_stop);
			sleep(WAIT_BETWEEN_EXECS);
			self::show_status("$app",$cmd_status);
		}		
	}
	
	
	public static function stop(){
		self::stop_app("MPD",cmd::$MPD_STATUS,cmd::MPD_STOP);
		self::stop_app("Squid",cmd::$SQUID_STATUS,cmd::SQUID_STOP);				
		self::stop_app("JRadius",cmd::$CADBIS_STATUS,cmd::CADBIS_STOP);
		self::stop_app("FreeRADIUS",cmd::$FREERADIUS_STATUS,cmd::FREERADIUS_STOP);
	}
	
	
	public static function close(){
		io::exec(cmd::IPFW_CLOSE_ALL);
	}	
	
	public static function open(){
		io::exec(cmd::IPFW_OPEN_ALL);
	}
	
	protected static function test_app($app){
		foreach($app as $el)
			self::test_app($el);
		
	}

	public static function status(){
		self::show_status("MPD",cmd::$MPD_STATUS);
		self::show_status("FreeRADIUS",cmd::$FREERADIUS_STATUS);
		self::show_status("Squid",cmd::$SQUID_STATUS);
		self::show_status("JRadius",cmd::$CADBIS_STATUS);
	}
}


class shell{	
	protected function quit(){die("\r\n");}
	protected function print_usage()
	{
		echo("Usage: cadbis [option] status | start | stop | restart | open | close \r\n option can have the following values: proxy");
	}
	protected function usage()
	{
		$this->print_usage();
		$this->quit();		
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
			case 'restart':
				proxy::restart();
			break;			
			case 'status':
				proxy::status();
			break;			
			default:
				$this->usage();
			break;
		}
	}
	
	public function __construct($argv, $argc)
	{	
		cmd::init();
		if($argc<2)
			$this->usage();
		switch($argv[1]){
			case 'proxy':
				$this->proceed_proxy($argv, $argc);
			break;
			case 'restart':
				echo "please wait while CADBiS restarting...\r\n";
				cadbis::restart();
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
			case 'status':
				cadbis::status();
			break;				
			default:
				$this->usage();				
		}
	}
};

new shell($_SERVER['argv'], $_SERVER['argc']);