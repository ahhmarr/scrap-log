<?php 
require 'vendor/autoload.php';
use GeoIp2\Database\Reader;
$reader = new Reader(getcwd().'/GeoLite2-City.mmdb');
// echo getcwd();
//read file
$testPath=getcwd()."/string.txt";
$mainPath="/home/dsstudios/logs/frontend/access_Adification.log";
$file=fopen($mainPath,"r");
$log=fopen("text.txt","w");

while(($row=fgets($file))!==false)
{
//parse for content
	echo $row."\n";
	$ip=$date=$file_name=$location="";
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
	echo "ip $ip  date $date location $location file_name $file_name";exit;
	if(!$ip || !$date || !$file_name || substr_count($ip, ".")<4)
		continue;
	$city=$reader->city($ip);
	$cityName=$city->city->name?",".$city->city->name:"";
	$location=$city->country->name.$cityName;
	
	$row=str_pad($date,25).
		 str_pad($ip, 25).
		 str_pad($file_name, 50).
		 str_pad($location,15)."\n";
	fwrite($log, $row);
}
fclose($log);
fclose($file);

//print relevant content
