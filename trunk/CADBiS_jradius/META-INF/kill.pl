#!/usr/local/bin/perl
$hostname="127.0.0.1";
$port="5005";
if( $ARGV[0] eq '' ) { die 'Usage: nasport'; };
$nasport=$ARGV[0];
use Net::Telnet ();

$t = new Net::Telnet (Timeout => 2,Port => $port,Binmode => '\015\012',
                               Prompt => '/\[\]/');
$t->open("$hostname");
$t->login("mpd", "password");

$t->print("bundle bnd".$nasport);
$t->print("");
$t->print("close");
$t->print("");
$t->print("exit");
$t->print("");
$t->close();
exit 0;