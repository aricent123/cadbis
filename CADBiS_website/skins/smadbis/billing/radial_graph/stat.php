<?php

 include("graph_class.php");
 $gr = new CGraph();

 if($type=="all")
 {

 //настройки графика
 $params=array();
 $params[0]=600;
 $params[1]=500;
 $params[2]=255;
 $params[3]=255;
 $params[4]=255;
 $params[5]=0;
 $params[6]=0;
 $params[7]=0;
 $params[8]=155;
 $params[9]=155;
 $params[10]=155;
 $params[11]=50;
 $params[12]=50;
 $params[13]="./Verdana.ttf";
 $params[14]="";
 $params[15]="";
 $params[16]="";
 $params[17]=6;
 $params[18]="";
 $params[19]=true;
 $params[20]=false;
 $params[21]="./Arial.ttf";
 $params[22]=10;
 $params[23]=11;
 $params[24]=true;
 $params[25]=300;
 $params[26]=30;

 //данные для прорисовки
 $data=array();
 $data[0]=12000;
 $data[1]=24000;
 $data[2]=30000;
 $data[3]=65000;
 $data[4]=12000;
 $data[5]=10000;
 $data[6]=10000;
 $data[7]=14501;
 $data[8]=14501;
 $data[9]=14501;
 $data[10]=14501;

 //метки для данных
 $labels=array();
 $labels[0]="SM";
 $labels[1]="DS";
 $labels[2]="Drypa";
 $labels[3]="Eugene";
 $labels[4]="Yoka";
 $labels[5]="Stasevich";
 $labels[6]="Murat";
 $labels[7]="ZPN";
 $labels[8]="Giljarow";
 $labels[9]="Kaspersky";
 $labels[10]="Admin";

 $gr->Draw($params,$data,$labels);
 }

 elseif($type=="compare")
 {


 //настройки графика
 $params=array();
 $params[0]=600;
 $params[1]=500;
 $params[2]=255;
 $params[3]=255;
 $params[4]=255;
 $params[5]=0;
 $params[6]=0;
 $params[7]=0;
 $params[8]=155;
 $params[9]=155;
 $params[10]=155;
 $params[11]=50;
 $params[12]=50;
 $params[13]="C:/Windows/Fonts/Verdana.ttf";
 $params[14]="";
 $params[15]="";
 $params[16]="";
 $params[17]=6;
 $params[18]="";
 $params[19]=true;
 $params[20]=false;
 $params[21]="C:/Windows/Fonts/Arial.ttf";
 $params[22]=10;
 $params[23]=11;
 $params[24]=true;
 $params[25]=300;
 $params[26]=30;

 //данные для прорисовки
 $data=array();
 $data[0]=12000;
 $data[1]=24000;
 $data[2]=30000;
 $data[3]=65000;
 $data[4]=12000;
 $data[5]=10000;
 $data[6]=10000;
 $data[7]=14501;
 $data[8]=14501;
 $data[9]=14501;
 $data[10]=14501;
 

 //метки для данных
 $labels=array();
 $labels[0]="SM";
 $labels[1]="DS";
 $labels[2]="Drypa";
 $labels[3]="Yoka";
 $labels[4]="ZPN";
 $labels[5]="Kaspersky";

 $gr->Draw($params,$data,$labels);
 }

?>