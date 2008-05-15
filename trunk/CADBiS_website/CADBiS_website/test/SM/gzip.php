<?php
// gzip.php v1.2 - read http://rm.pp.ru/?1.phpgzip 
// released on 2004-05-06, by Roman Mamedov<roman at rm.pp.ru> 
// license: do with this code whatever you want. 

///// Configuration ////////////////// 
$PREFER_DEFLATE = false; // prefer deflate over gzip when both are supported 
$FORCE_COMPRESSION = false; // force compression even when client does not report support 
////////////////////////////////////// 

function compress_output_gzip($output) { 
   return gzencode($output); 
} 

function compress_output_deflate($output) { 
   return gzdeflate($output, 9); 
} 

if(isset($_SERVER['HTTP_ACCEPT_ENCODING'])) 
   $AE = $_SERVER['HTTP_ACCEPT_ENCODING']; 
else 
   $AE = $_SERVER['HTTP_TE']; 

$support_gzip = (strpos($AE, 'gzip') !== FALSE) || $FORCE_COMPRESSION; 
$support_deflate = (strpos($AE, 'deflate') !== FALSE) || $FORCE_COMPRESSION; 

if($support_gzip && $support_deflate) { 
   $support_deflate = $PREFER_DEFLATE; 
} 

if ($support_deflate) { 
   header("Content-Encoding: deflate"); 
   ob_start("compress_output_deflate"); 
} else{ 
   if($support_gzip){ 
       header("Content-Encoding: gzip"); 
       ob_start("compress_output_gzip"); 
   } else { 
       ob_start(); 
   } 
} 