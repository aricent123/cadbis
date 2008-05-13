<html><body>

<?php

//include charts.php to access the SendChartData function
include "charts.php";

//echo InsertChart ( "charts.swf", "charts_library", "data.php?chart_type=loading",600, 400 );//нагрузка на канал
echo InsertChart ( "charts.swf", "charts_library", "data.php?chart_type=topurl&uid=0&gid=1&limit=50",800, 400 );//topurl
?>

</body></html>
