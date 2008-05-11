<html><body>

<?php

//include charts.php to access the SendChartData function
include "charts.php";

echo InsertChart ( "charts.swf", "charts_library", "data.php?chart_type=loading",600, 400 );
?>

</body></html>
