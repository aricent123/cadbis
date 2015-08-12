для вызова компонента используется
```
echo InsertChart ( "charts.swf", "charts_library", "index.php", 1000, 500,CCCCCC,false,"KK" );
```
где index.php - файл, вот такого содержания:
```
<?php

//include charts.php to access the SendChartData function
include "charts.php";

$chart [ 'chart_data' ] = array ( array ( "0",         "10", "20", "30", "40", "50" , "60"  ),
                                  array ( "Jan",     10,     10,     15,     10,     10,     10  ),
                                  array ( "Feb",   10,     20,     15,     15,     10,     20  )
                                );
$chart [ 'chart_type' ] = "bar";

$chart [ 'legend_label' ] = array (   'layout'  =>  "vertical",
                                      'bullet'  =>  "circle",
                                      'font'    =>  "Arial", 
                                      'bold'    =>  true, 
                                      'size'    =>  12, 
                                      'color'   =>  "88FF00", 
                                      'alpha'   =>  90
                                  ); 
echo SendChartData ($chart);

?>
```

или в  сгенерированном HTML это выглядит так:

```

<chart>
	<chart_data>
		<row>
			<string>0</string>
			<string>10</string>
			<string>20</string>
			<string>30</string>

			<string>40</string>
			<string>50</string>
			<string>60</string>
		</row>
		<row>
			<string>Jan</string>
			<number>10</number>

			<number>10</number>
			<number>15</number>
			<number>10</number>
			<number>10</number>
			<number>10</number>
		</row>

		<row>
			<string>Feb</string>
			<number>10</number>
			<number>20</number>
			<number>15</number>
			<number>15</number>

			<number>10</number>
			<number>20</number>
		</row>
	</chart_data>
	<chart_type>bar</chart_type>
	<legend_label layout="vertical" bullet="circle" font="Arial" bold="1" size="12" color="88FF00" alpha="90" />
</chart>

```

это сама функция которая вставляет. что внутри происходит - ппц
```
function InsertChart( $flash_file, $library_path, $php_source, $width=1000, $height=500, $bg_color="666666", $transparent=false, $license="XN69-IWCH7RVPJQF3Y4G8T05A1LB.2OPDEKZUR" ){
	
	$php_source=urlencode($php_source);
	$library_path=urlencode($library_path);
	$html="<OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' ";
	$html.="WIDTH=".$width." HEIGHT=".$height." id='charts' ALIGN=''>";
	$u=(strpos ($flash_file,"?")===false)? "?" : ((substr($flash_file, -1)==="&")? "":"&");
	$html.="<PARAM NAME=movie VALUE='".$flash_file.$u."library_path=".$library_path."&stage_width=".$width."&stage_height=".$height."&php_source=".$php_source;
	if($license!=null){$html.="&license=".$license;}
	$html.="'> <PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#".$bg_color."> ";
	if($transparent){$html.="<PARAM NAME=wmode VALUE=transparent> ";}
	$html.="<EMBED src='".$flash_file.$u."library_path=".$library_path."&stage_width=".$width."&stage_height=".$height."&php_source=".$php_source;
	if($license!=null){$html.="&license=".$license;}
	$html.="' quality=high bgcolor=#".$bg_color." WIDTH=".$width." HEIGHT=".$height." NAME='charts' ALIGN='' swLiveConnect='true' ";
	if($transparent){$html.="wmode=transparent ";}
	$html.="TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer'></EMBED></OBJECT>";
	return $html;
	
}
```