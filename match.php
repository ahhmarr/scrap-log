<?php 
require 'vendor/autoload.php';
use GeoIp2\Database\Reader;
$reader = new Reader(getcwd().'/GeoLite2-City.mmdb');
// echo getcwd();
//read file
$file=fopen(getcwd()."/string.txt","r");
while(($row=fgets($file))!==false)
{
//parse for content
	$ip=$date=$file_name="";
	preg_match('/^[0-9.]*/',$row,$match);
	$ip=(isset($match[0]))?$match[0]:"";
	preg_match('/\[.*\]/', $row,$match);
	$date=(isset($match[0]))?$match[0]:"";
	if($date)
	{
		$date=str_replace("+0000", "", $date);
		$date=str_replace("[", "", $date);
		$date=str_replace("]", "", $date);
	}
	preg_match('/"GET \/.*.plist/',$row,$match);
	$file_name=(isset($match[0]))?$match[0]:"";
	if($file_name)
	{
		$file_name=str_replace('"GET /',"",$file_name);
	}
	$city=$reader->city($ip);
	var_dump($city);exit;

}
fclose($file);
//print relevant content