#!/usr/local/bin/php
<?
class cmd{
	const IPFW_PROXY_ADD = 'ipfw add 320 fwd 127.0.0.1,8888 tcp from 10.0.0.0/8 to any 80';
	const IPFW_PROXY_DELETE  = 'ipfw delete 320';
	const SQUID_START = 'squid';
	const SQUID_STOP = 'squid -k stop';
	const MPD_START = 'mpd4 -b &';
	const MPD_STOP = 'mpd4 --kill | killall mpd4 &';
	const CADBIS_START = 'java -jar -server /home/smecsia/cadbis/daemon/cadbis.jar /home/smecsia/cadbis/daemon/conf/jradius-config.xml > /home/smecsia/cadbis/daemon/cadbis.log &';
	const CADBIS_STOP = 'killall java';	
	const FREERADIUS_START = 'radiusd &';
	const FREERADIUS_STOP = 'killall radiusd';		
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
		shell::exec(cmd::FREERADIUS_START);
		#shell::exec(cmd::SQUID_START);
		shell::exec(cmd::CADBIS_START);
		
	}
	public static function stop(){
		shell::exec(cmd::MPD_STOP);
		shell::exec(cmd::FREERADIUS_STOP);
		#shell::exec(cmd::SQUID_STOP);
		shell::exec(cmd::CADBIS_STOP);		
	}
}


class shell{	
	protected function quit(){die("\r\n");}
	protected function print_usage()
	{
		echo("Usage: cadbis [option] start | stop \r\n option can have the following values: proxy");
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

	protected function proceed_proxy($argv, $argc)
	{
		if($argc<3)
			$this->usage();
		switch($argv[3]){
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
				cadbis::start();
			break;
			case 'stop':
				cadbis::stop();
			break;
			default:
				$this->usage();				
		}
	}
};

new shell($_SERVER['argv'], $_SERVER['argc']);