<?php
$item=__DIR__."/Electronics.html";
$item=__DIR__."/Computer.html";
$item=__DIR__."/DataAnalysis.html";

//$limit=0;
$doc = new DOMDocument();

if (@$doc->loadHTML(file_get_contents($item)))
{
	//var_dump($doc);
	$xpath = new DOMXPath($doc);
	$query = '//a[@class="course-link"]';
	$descs = $xpath->query($query);
	//echo "\n".$item."\n";
	//foreach($descs as $desc){echo "\n".$desc;}
	foreach ($descs as $des) {
			echo "\n".$des->getAttribute('href');
	}
	
}
else 
{ echo "\nERROR ".$item;}
	
	



?>
