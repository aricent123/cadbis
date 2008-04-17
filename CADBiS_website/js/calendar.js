/*	CSTruter PHP Calendar Control JS File version 1.0
	Author: Christoff Truter

	Date Created: 3 November 2006
	Last Update: 20 November 2006

	e-Mail: christoff@cstruter.com
	Website: www.cstruter.com
	Copyright 2006 CSTruter				*/


//var months = ["January","February","March","April","May","June", "July","August","September","October","November","December"];
var months = ["Январь","Февраль","Март","Апрель","Май","Июнь", "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];

function isLeapYear(year) 
{
	return new Date(year,2-1,29).getDate()==29;
}

function TotalDays(month,year)
{
	var days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	return (month==1 && isLeapYear(year)) ? 29 : days[month];
}


function Calendar(ID,date)
{
	/* Private */ 	var fromDate = new Date();
	/* Private */ 	var today = new Date();
	/* Private */	var year;
	/* Private */	var month;
	/* Private */ 	var day;	
	/* Private */	var currentDateStyle;		// Style for current date
	/* Private */	var selectedDateStyle;		// Style for selected dates
	/* Private */	var normalDateStyle;		// Style for unselected dates
	/* Private */   var availableDateStyle;		//

	fromDate.setDate(1);
	
	
	if (document.all)
	{
		window.attachEvent('onload',attachCalendarEvent);
	}
	else
	{
		window.addEventListener('load',attachCalendarEvent,true);
	}

	/* Private */ function UID(Name)
	{
		return ID + Name;
	}

	/* Private */ function setDate()
	{
		year = fromDate.getFullYear();
		month = fromDate.getMonth();
		day = fromDate.getDay();
		alert(UID("Month"));
		alert(months[month]+" "+year);
		document.getElementById(UID("Month")).innerHTML = months[month]+" "+year;
	}

	/* Private */ function setSelectedDate(SelDay)
	{
		var selectedObj = document.getElementById(UID('item['+(SelDay+day)+']')).className = selectedDateStyle;
		var linkObj = document.getElementById(UID('hlink['+(SelDay+day)+']')).className = selectedDateStyle;
	}

	/* Private */ function setToday()
	{
		var Today = today.getDate();

		if (today.getMonth()==month && today.getFullYear()==year)
		{
			document.getElementById(UID("item["+(Today+fromDate.getDay())+"]")).className = currentDateStyle;
			document.getElementById(UID("hlink["+(Today+fromDate.getDay())+"]")).className = currentDateStyle;
		}
	}

	/* Public */  this.setStyles = function()
	{
		currentDateStyle = this.currentDateStyle;
		selectedDateStyle = this.selectedDateStyle;		
		normalDateStyle =  this.normalDateStyle;
	}

	/* Public */ this.setCalendarInput = function(day)
	{
		for (i=1;i<=42;i++)
		{
			var Obj = document.getElementById(UID("item["+i+"]"));
			var LObj = document.getElementById(UID("hlink["+i+"]"));
			Obj.className = normalDateStyle;

			if (LObj != null) LObj.className = normalDateStyle;
		}

		setToday();
		setSelectedDate(day);

		var inpObj = document.getElementById(UID('calendar'));
		inpObj.value = year + "-" + (month+1) + "-" + day;
	}

	/* Private */ function attachCalendarEvent()
	{
		var DateSet = (date == undefined) ? false : true;

		document.getElementById(UID('navigateback')).href = "javascript:"+UID('calendar')+".previousMonth()";
		document.getElementById(UID('navigatenext')).href = "javascript:"+UID('calendar')+".nextMonth()";
		
		if (DateSet)
		{
			var DateString = date.split("-");
			fromDate.setYear(DateString[0]);
			fromDate.setMonth((DateString[1]-1));
		}

		setCalendar();

		if (DateSet)
		{
			setSelectedDate(parseInt(DateString[2]));
		}
	}
	
	
	function addAvailDates(id)
	{
		if(this.availDates == null)
			this.availDates = new Array();
		this.availDates.concat(id);
	}
	
	function isDateAvailable(id)
	{
	if(this.availDates == null)
		return true;
		for(var i=0;i<this.availDates.Length;++i)
		  if(this.availDates[i] == id)
		  	return true;		  	
		return false;
	}

	/* Private */ function setCalendar()
	{
		setDate();
		for (i=1;i<=42;i++)
		{
			var Obj = document.getElementById(UID("item["+i+"]"));
			Obj.innerHTML="";
			Obj.className = normalDateStyle;
		}
		

		for (i=1;i<=TotalDays(month,year);i++)
		{
			if(isDateAvailable(UID("item["+(i+day)+"]")))
				document.getElementById(UID("item["+(i+day)+"]")).innerHTML='<a class="'+normalDateStyle+'" id="'+UID('hlink['+(i+day)+']')+'" href="javascript:'+UID('calendar')+'.setCalendarInput(' + i + ')">' + i + '</a>';
		}
		setToday();
	}

	/* Public */ this.selectMonth = function(month)
	{
		fromDate.setMonth(month);
		setCalendar();
	}

	/* Public */ this.nextMonth = function()
	{
		fromDate.setMonth(fromDate.getMonth()+1);
		setCalendar();		
	}

	/* Public */ this.previousMonth = function()
	{
		fromDate.setMonth(fromDate.getMonth()-1);
		setCalendar();
	}

}