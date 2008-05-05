<?php
class PacketsTodayLimits
{
	/**
	 * CBilling object
	 * @var CBilling
	 */
	protected $BILL;
	
	protected $packets = null;
	protected $packetsCoefs = null;
	protected $allowedDayTraffic = null;
	protected $dayTrafficLimits = null;
	protected $monthTrafficLimits = null;	
	
	/**
	 * 
	 * @param CBilling $BILL
	 */
	public function __construct($BILL)
	{
		$this->BILL = $BILL;
		$this->packets = $BILL->GetTarifs();
	}
	
	/**
	 * recalculates packets limits
	 *
	 * @param array $packets
	 */
	protected function recalcTrafficLimits()
	{
		$config = $this->BILL->GetCADBiSConfig();
		$maximumMonthTraffic = $config['max_month_traffic'];  
		$monthAccts = $this->BILL->GetMonthTotalAccts();
		$usedMonthTraffic = $monthAccts['traffic'];
		$restDaysCount = date("t")-date("j");	
		$restMonthTraffic = $maximumMonthTraffic - $usedMonthTraffic;
		$this->allowedDayTraffic = ($restMonthTraffic) / $restDaysCount;
			
		$SumOfRangs = 0; 
		for($i = 0; $i< count($this->packets); ++$i)
			$SumOfRangs += $this->packets[$i]['rang'] * $this->packets[$i]['simuluse_sum'];
		for($i = 0; $i< count($this->packets); ++$i)
			$this->packetsCoefs[$this->packets[$i]['gid']] = ((double)$this->packets[$i]['rang'] * (double)$this->packets[$i]['simuluse_sum'])/(double)$SumOfRangs;
				
		for($i = 0; $i< count($this->packets); ++$i)
		{
			$dayLimit = round($this->allowedDayTraffic * $this->packetsCoefs[$this->packets[$i]['gid']]);
			$restPacketMonthTraffic = $dayLimit*$restDaysCount;
			$this->monthTrafficLimits[$this->packets[$i]['gid']]= $restPacketMonthTraffic;
			if($this->packets[$i]['exceed_times']*$dayLimit<=$restPacketMonthTraffic)
				$dayLimit *= $this->packets[$i]['exceed_times']+1;
			$this->dayTrafficLimits[$this->packets[$i]['gid']] = $dayLimit;
		}
	}
	
	/**
	 * Returns allowed traffic for a day
	 *
	 */
	public function getAllowedDayTraffic()
	{
		if($this->allowedDayTraffic == null)
			$this->recalcTrafficLimits();		
		return $this->allowedDayTraffic;
	}
	
	/**
	 * get traffic limit for a given gid
	 *
	 * @param int $gid
	 * @return int
	 */
	public function getPacketDayTrafficLimit($gid)
	{
		if($this->dayTrafficLimits == null)
			$this->recalcTrafficLimits();
		return $this->dayTrafficLimits[$gid];
	}
};