#!/usr/bin/perl

if( $ARGV[3] eq '' ) { die 'Usage: mpddown user nasip userip nasport'; };

$linkname='pptp';
$maxng=99;
$user=$ARGV[0];
$nasip=$ARGV[1];
$userip=$ARGV[2];
$nasport=$ARGV[3]; if( $nasport > $maxng ) { $nasport=1; };
$nastelnetport=5005;

use IO;

sub checklink;

$sock = IO::Socket::INET->new(
PeerAddr => $nasip,
PeerPort => $nastelnetport,
Proto => 'tcp') or die "Can not connect to mpd!\n$!";
$sock->autoflush(1);
while (<$sock>){ print; last; };
while (<$sock>){ print; last; };
while (<$sock>){ print; last; };
printf($ARGV[0]);
$portn=$nasport;
sprintf("PORT NO %d",portn);
checklink;
if( $user eq $luser ) {
print $sock "close\n"; }
else {
$portn++;
while( ($portn != $nasport) && ($user ne $luser) ) {
if( $portn > $maxng ) { $portn=0; };
checklink;
if( $user eq $luser ) {
print $sock "close\n";
};
$portn++;
} ;
};
close $sock;
exit 0;
sub checklink {
print $sock "link pptp",$portn,"\n";
print $sock "show radius\n";
while (<$sock>){
print;
@words=split(' ');
$luser=$words[2];
last if( $words[0] eq 'Authname' ); };
};

