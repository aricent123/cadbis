<?php

/*
ini_set(safe_mode, false);
ini_set(upload_tmp_dir,"sml");
ini_set(file_uploads,true);
*/
error_reporting(E_PARSE);


extract($_GET); 
extract($_POST); 
extract($_COOKIE); 
extract($HTTP_SESSION_VARS); 
extract($_SESSION);
extract($_FILES);


if (is_array($_GET)) {
foreach($_GET as $key => $val) {
if (!is_array($val)) {
if (get_magic_quotes_gpc) $val = StripSlashes($val);
$val = AddSlashes(StripSlashes($val));
}
$$key = $val;
}
} elseif (is_array($HTTP_GET_VARS)) {
foreach($HTTP_GET_VARS as $key => $val) {
if (!is_array($val)) {
if (get_magic_quotes_gpc) $val = StripSlashes($val);
$val = AddSlashes(StripSlashes($val));
}
$$key = $val;
}
}

if (is_array($_POST)) {
foreach($_POST as $key => $val) {
if (!is_array($val)) {
if (get_magic_quotes_gpc) $val = StripSlashes($val);
$val = AddSlashes(StripSlashes($val));
}
$$key = $val;
}
} elseif (is_array($HTTP_POST_VARS)) {
foreach($HTTP_POST_VARS as $key => $val) {
if (!is_array($val)) {
if (get_magic_quotes_gpc) $val = StripSlashes($val);
$val = AddSlashes(StripSlashes($val));
}
$$key = $val;
}
}


?>