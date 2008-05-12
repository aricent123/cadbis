<?
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}
