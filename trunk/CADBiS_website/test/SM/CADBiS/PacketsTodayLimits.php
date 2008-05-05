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
	protected $usedMonthTraffic = null;
	protected $restMonthTraffic = null;
	protected $restDaysCount = null;
	protected $onePointCost = null;
	
	
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
		$this->usedMonthTraffic = ($monthAccts['traffic'])?$monthAccts['traffic']:0;
		$this->restDaysCount = date("t")-date("j");	
		$this->restMonthTraffic = $maximumMonthTraffic - $this->usedMonthTraffic;
		$this->allowedDayTraffic = ($this->restMonthTraffic) / $this->restDaysCount;
			
		$SumOfRangs = 0; 
		for($i = 0; $i< count($this->packets); ++$i)
			$SumOfRangs += $this->packets[$i]['rang'] * $this->packets[$i]['simuluse_sum'];
		for($i = 0; $i< count($this->packets); ++$i)
			$this->packetsCoefs[$this->packets[$i]['gid']] = ((double)$this->packets[$i]['rang'] * (double)$this->packets[$i]['simuluse_sum'])/(double)$SumOfRangs;
		
		$this->onePointCost = round($this->allowedDayTraffic/(double)$SumOfRangs);
		
		for($i = 0; $i< count($this->packets); ++$i)
		{
			$dayLimit = round($this->allowedDayTraffic * $this->packetsCoefs[$this->packets[$i]['gid']]);
			$restPacketMonthTraffic = $dayLimit*$this->restDaysCount;
			$this->monthTrafficLimits[$this->packets[$i]['gid']]= $restPacketMonthTraffic;
			if($this->packets[$i]['exceed_times']*$dayLimit<=$restPacketMonthTraffic)
				$dayLimit *= $this->packets[$i]['exceed_times']+1;
			else
				$dayLimit = $restPacketMonthTraffic;
			$this->dayTrafficLimits[$this->packets[$i]['gid']] = $dayLimit;
		}
	}
	
	/**
	 * Returns allowed traffic for a day
	 * @return int
	 */
	public function getAllowedDayTraffic()
	{
		if($this->allowedDayTraffic == null)
			$this->recalcTrafficLimits();		
		return $this->allowedDayTraffic;
	}
	
	/**
	 * Returns rest days of month
	 * @return int
	 */
	public function getRestDaysCount()
	{
		if($this->restDaysCount == null)
			$this->recalcTrafficLimits();		
		return $this->restDaysCount;
	}	
	
	/**
	 * Returns rest month traffic
	 * @return int
	 */
	public function getRestMonthTraffic()
	{
		if($this->restMonthTraffic == null)
			$this->recalcTrafficLimits();		
		return $this->restMonthTraffic;
	}	
	
	/**
	 * Returns used month traffic
	 * @return int
	 */
	public function getUsedMonthTraffic()
	{
		if($this->usedMonthTraffic == null)
			$this->recalcTrafficLimits();		
		return $this->usedMonthTraffic;
	}		
	
	/**
	 * Returns cost of 1 point of rang
	 * @return int
	 */
	public function getOnePointCost()
	{
		if($this->onePointCost == null)
			$this->recalcTrafficLimits();		
		return $this->onePointCost;
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