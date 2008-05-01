<?

/*	CSTruter PHP Calendar Control version 1.0
	Author: Christoff Truter

	Date Created: 3 November 2006
	Last Update: 12 November 2006

	e-Mail: christoff@cstruter.com
	Website: www.cstruter.com
	Copyright 2006 CSTruter				*/

$NEED_PHP_SELF = false;

require_once(dirname(__FILE__)."/../control.inc.php");


class calendar extends smphp_control
{
	private $RUS_MONTHS = array("Январь","Февраль","Март","Апрель","Май","Июнь", "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь");
	private $year;		// Current Selected Year
	private $month;		// Current Selected Month
	private $day;		// Current Selected Day
	private $output;	// Contains the Rendered Calendar
	private $date;		// Contains the date preset via Constructor
	public  $redirect;  	// Page to redirect to when a specific date is selected
	public  $inForm;    	// Use Object in a form
	public $add_params_sel,$add_params_day;

	private $availDates = null;
	protected function add_params_sel()
	{
		$out = '';
		if(count($this->add_params_sel))
			foreach($this->add_params_sel as $param=>$value)
			{
				$out .= ($out?'&':'').$param.'='.$value;
			}
		return $out;
	}
	protected function add_params_day()
	{
		$out = '';
		if(count($this->add_params_day))
			foreach($this->add_params_day as $param=>$value)
			{
				$out .= ($out?'&':'').$param.'='.$value;
			}
		return $out;
	}
	public function get_availDates()
	{
		return $this->availDates;
	}

	public function set_availDates($value)
	{
		$this->availDates = $value;
	}

	public function add_availDate($time)
	{
		if(is_null($this->availDates))
			$this->availDates = array();
		$this->availDates[] = $time;
	}
	
	protected function serdate($d, $m, $y)
	{
		return (($d<10)?'0'.$d:$d).(($m<10)?'0'.$m:$m).$y;	
	}
	
	public function isDayAvailable($day)
	{
		if($this->availDates == null)
			return false;
		for($i=0;$i<count($this->availDates);++$i)
		if($this->serdate($day,$this->month,$this->year) == date("dmY",$this->availDates[$i]))
				return true;
		return ($this->serdate($day,$this->month,$this->year) == date("dmY"));
	}

	// Styles used - referenced from CSS - with their default values assigned

	public $currentDateStyle = "currentDate";		// Style for current date
	public $selectedDateStyle = "selectedDate";		// Style for selected dates
	public $normalDateStyle = "normalDate";			// Style for unselected dates
	public $availDateStyle = "availDate";
	public $navigateStyle = "navigateYear";			// Style used in navigation "buttons"
	public $monthStyle = "month";				// Style used to display month
	public $daysOfTheWeekStyle = "daysOfTheWeek";		// Styles used to display sun-mon


	protected function getURL()
	{
		global $NEED_PHP_SELF;
		return ($NEED_PHP_SELF)?$_SERVER['PHP_SELF']:"";
	}

	// Constructor - Assign an unique ID to your instantiated object, if needed. Date Format = YYYY-MM-DD

	public function calendar($ID, $Date = NULL)
	{
 		$this->id = $ID."_";
		$this->date = isset($Date) ? $Date: NULL;

		if (isset($_REQUEST[$this->UID('year')]))
		{
			$this->year = $_REQUEST[$this->UID('year')];
			$this->month = $_REQUEST[$this->UID('month')];
			$this->day = $_REQUEST[$this->UID('day')];
		}
		else
		{
			if (isset($Date))
			{
				$DateComponents = explode("-",$Date);
				$this->year = $DateComponents[0];
				$this->month = $DateComponents[1];
				$this->day = isset($_REQUEST[$this->UID('day')]) ? $_REQUEST[$this->UID('day')] : $DateComponents[2];
			}
			else
			{
				$this->year = date("Y");
				$this->month = date("n");
				$this->day = date("j");
			}
		}
	}

	// Sets the current Month and Year for the instantiated object

	private function set_date()
	{
		if ($this->month > 12)
		{
			$this->month=1;
			$this->year++;
		}

		if ($this->month < 1)
		{
			$this->month=12;
			$this->year--;
		}

		if ($this->year > 2037) $this->year = 2037;
		if ($this->year < 1971) $this->year = 1971;
	}


	public function value()
	{
		$returnValue="";

		if (isset($_REQUEST[$this->UID('day')]))
		{
			$returnValue = isset($this->day) ? $this->year.'-'.$this->month.'-'.$_REQUEST[$this->UID('day')]: '';
		}
		else if (isset($_REQUEST[$this->UID('calendar')]))
		{
			$returnValue = $_REQUEST[$this->UID('calendar')];
		}
		else if (isset($this->date))
		{
			$returnValue = isset($this->day) ? $this->year.'-'.$this->month.'-'.$this->day: '';
		}

		return $returnValue;
	}

	// Render the calendar, and add it to a variable - needed for placing the object in a specific area in our output buffer

	public function render()
	{
		$days = 1;
		$this->redirect = isset($this->redirect) ? $this->redirect: $this->getURL() ;
		$this->set_date();
		$total_days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		$first_spaces = date("w", mktime(0, 0, 0, $this->month, 1, $this->year));
		$currentday = $this->UID('day');

		if (isset($this->inForm))
		{
			$CObjID = $this->UID('calendar');
			$DateString = ($this->Value()) ? '","'.$this->Value() : '';
			$this->output = '<script language="javascript">'."\n".'var '.$CObjID.' = new Calendar("'.$this->ID.$DateString.'");'."\n"
			.$CObjID.'.currentDateStyle = "'.$this->currentDateStyle.'";'."\n"
			.$CObjID.'.selectedDateStyle = "'.$this->selectedDateStyle.'";'."\n"
			.$CObjID.'.normalDateStyle = "'.$this->normalDateStyle.'";'."\n"
			.$CObjID.'.setStyles();'."\n"
			.'</script>'."\n"
			.'<input type="hidden" id="'.$CObjID.'" name="'.$CObjID.'" value="'.$this->Value().'"/>'."\n";
		}
		else $this->output = '';

		$NavUrls = $this->url_params($this->UID('year'),$this->UID('month'),$this->UID('day'),array_keys($this->add_params_sel));

		$this->output.= '<table class="calendar"><tr><td class="'.$this->navigateStyle.'"><a id="'.$this->UID('navigateback').'" class="'.$this->navigateStyle.'" href="'.$this->getURL().
			'?'.$this->add_params_sel().'&'.$this->UID('month').'='.($this->month-1).'&'.$this->UID('year').'='.$this->year.$NavUrls.'"><</a>
		    </td><td id="'.$this->UID('Month').'" colspan="5" class="'.$this->monthStyle.'">'.$this->RUS_MONTHS[date("n", mktime(0, 0, 0, $this->month, 1, $this->year))-1].'&nbsp;'.$this->year.'
		    </td><td class="'.$this->navigateStyle.'"><a id="'.$this->UID('navigatenext').'" class="'.$this->navigateStyle.'" href="'.$this->getURL().'?'.$this->add_params_sel().'&'.$this->UID('month').'='.($this->month+1).'&'.$this->UID('year').'='.$this->year.$NavUrls.'">></a>
		    </td></tr><tr class="'.$this->daysOfTheWeekStyle.'"><td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td></tr>';

        	for ($Week=0;$Week<6;$Week++)
        	{
            	$this->output.= '<tr>';

				for ($Day=0;$Day<7;$Day++)
            	{

					$days++;
					$dDay = $days - $first_spaces;
					$norm_style = ($this->isDayAvailable($dDay))?$this->availDateStyle:$this->normalDateStyle;
					
//					echo('dDay='.$dDay.'<br/>avail dates:');
//					foreach($this->availDates as $date)
//						echo(date('d/m/Y',$date).'<br/>');

					$CellID = $this->UID('item['.$days.']');

					if ($days > $first_spaces && ($dDay) < $total_days  + 1)
					{
						$LinkID = $this->UID('hlink['.$days.']');
						$currentSelectedDay = '<td id="'.$CellID.'" class="'.$this->selectedDateStyle.'"><a id="'.$LinkID.'" class="'.$this->selectedDateStyle.'"';
						$CurrentDate = isset($_REQUEST[$currentday]) ? $_REQUEST[$currentday]: '';

						if ($CurrentDate == $dDay)	$this->output.= $currentSelectedDay;
						else
						{
							$this->output.='<td id="'.$CellID.'" class=';
							$this->output.= ($dDay==date("j") && $this->year==date("Y") && $this->month==date("n")) ?
								'"'.$this->currentDateStyle.'"><a id="'.$LinkID.'" class="'.$this->currentDateStyle.'"' :
								'"'.$norm_style.'"><a id="'.$LinkID.'" class="'.$this->normalDateStyle.'"';
						}

						if($this->isDayAvailable($dDay))
							$this->output.= 'href="'.$this->redirect.'?'.$this->add_params_day().'&'.$currentday.'='.$dDay.$this->url_params($currentday,array_keys($this->add_params_day)).'">'.$dDay.'</a></td>';
						else
							$this->output.= '>'.$dDay.'</a></td>';


					}
					else
					{
						$this->output.='<td id="'.$CellID.'" class="'.$this->normalDateStyle.'"></td>'."\n";
					}
				}

				$this->output.="</tr>";
        	}

		$this->output.= '</table>';

		return $this->output;
	}
}
