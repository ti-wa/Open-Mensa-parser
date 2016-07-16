<?php
$r =  file_get_contents("http://www.max-manager.de/daten-extern/seezeit/xml/mensa_htwg/speiseplan.xml");
$xml=simplexml_load_string($r);
$newxml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><openmensa version= "2.0" xmlns="http://openmensa.org/open-mensa-v2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://openmensa.org/open-mensa-v2 http://openmensa.org/open-mensa-v2.xsd"></openmensa>');  
$newxml->addChild("canteen");
$i = -1;
foreach ($xml->tag as $day) { 
	$i = $i +1;	
	$currentDay=$newxml->canteen->addChild("day"); 
	$timestamp=$day->attributes(); 
	$timestamp= $timestamp + 84400;
	$time= gmdate("Y-m-d", $timestamp *1); 
	$currentDay->addAttribute("date", $time);
	foreach($day -> item as $item) { 
		$category=$currentDay->addChild("category");
		$category->addAttribute("name",$item -> category);
		$meal=$category->addChild("meal");	
		$meal->addChild("name");
		$meal->name = $item -> title;	
		$meal->addChild("price");
		$preis= $item -> preis1;	
		$meal->price = str_replace(",", ".", $preis);
		$meal->price->addAttribute("role", "student");	
	}
}
Header('Content-type: text/xml');
echo $newxml->asXML();	 
?>
