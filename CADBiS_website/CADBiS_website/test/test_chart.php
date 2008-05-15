<html><body>

<?php

//include charts.php to access the SendChartData function
include "../skins/smadbis/billing/cadbisnew/graph/charts.php";

echo InsertChart ( "../skins/smadbis/billing/cadbisnew/graph/charts.swf", "../skins/smadbis/billing/cadbisnew/graph/charts_library", "../skins/smadbis/billing/chart_data.php?chart_type=loading",600, 400 );//нагрузка на канал
echo InsertChart ( "../skins/smadbis/billing/cadbisnew/graph/charts.swf", "../skins/smadbis/billing/cadbisnew/graph/charts_library", "../skins/smadbis/billing/chart_data.php?chart_type=topurl&uid=16&gid=6&limit=50",800, 400 );//topurl
echo InsertChart ( "../skins/smadbis/billing/cadbisnew/graph/charts.swf", "../skins/smadbis/billing/cadbisnew/graph/charts_library", "../skins/smadbis/billing/chart_data.php?chart_type=today&uid=16&gid=6&limit=50",800, 400 );//today
echo InsertChart ( "../skins/smadbis/billing/cadbisnew/graph/charts.swf", "../skins/smadbis/billing/cadbisnew/graph/charts_library", "../skins/smadbis/billing/chart_data.php?chart_type=month&uid=16&gid=6&limit=50",800, 400 );//month
echo InsertChart ( "../skins/smadbis/billing/cadbisnew/graph/charts.swf", "../skins/smadbis/billing/cadbisnew/graph/charts_library", "../skins/smadbis/billing/chart_data.php?chart_type=week&uid=16&gid=6&limit=50",800, 400 );//week
echo InsertChart ( "../skins/smadbis/billing/cadbisnew/graph/charts.swf", "../skins/smadbis/billing/cadbisnew/graph/charts_library", "../skins/smadbis/billing/chart_data.php?chart_type=tarifs&tarif=!all!&fdate=2004-01-01&tdate=2008-12-15",800, 400 );//tarifs
?>

</body></html>
