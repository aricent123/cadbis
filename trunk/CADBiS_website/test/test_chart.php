<html><body>

<?php

//include charts.php to access the SendChartData function
include "../skins/smadbis/billing/cadbisnew/graph/charts.php";

//echo InsertChart ( "charts.swf", "charts_library", "data.php?chart_type=loading",600, 400 );//нагрузка на канал
echo InsertChart ( "../skins/smadbis/billing/cadbisnew/graph/charts.swf", "charts_library", "../../chart_data.php?chart_type=topurl&uid=16&gid=6&limit=50",800, 400 );//topurl
?>

</body></html>
