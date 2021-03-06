<?php 
require 'vendor/autoload.php';
use GeoIp2\Database\Reader;
$reader = new Reader('/home/dsstudios/plist-logger/GeoLite2-City.mmdb');
//read file
foreach(["",".1",".2",".3",".4",".5",".6",".7"] as $value)
{
	writeLogEntries("/home/dsstudios/logs/frontend/access_Adification.log$value",
					"/home/dsstudios/webapps/htdocs/plist.log$value");
}
function writeLogEntries($mainPath,$logPath)
{
	global $reader;
	$file=fopen($mainPath,"r");
	$log=fopen($logPath,"w");

	while(($row=fgets($file))!==false)
	{
	//parse for content
		// echo $row."\n";
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
		preg_match('/"GET \/.*.plist HTTP\/1.1" 200/',$row,$match);
		$file_name=(isset($match[0]))?$match[0]:"";
		if($file_name)
		{
			$file_name=str_replace('"GET /',"",$file_name);
			$file_name=str_replace(' HTTP/1.1" 200',"",$file_name);
		}
		if(!$ip || !$date || !$file_name || substr_count($ip, ".")<3)
			continue;
		try
		{
			$city=$reader->city($ip);
			$cityName=$city->city->name?",".$city->city->name:"";
			$location=$city->country->name.$cityName;	
		}
		catch(Exception $e)
		{
			$location="Not Found";
		}
		$row=str_pad($date,25).
			 str_pad($ip, 25).
			 str_pad($file_name, 50).
			 str_pad($location,15)."\n";
		fwrite($log, $row);
	}
	fclose($log);
	fclose($file);
}

//print relevant content
