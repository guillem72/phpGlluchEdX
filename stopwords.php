<?php

//var_dump(get_stops());
//var_dump(is_word("esos           |  those"));

function get_stops(){
$filename="lang/stopwords_es.txt";
$snowball=explode("\n",file_get_contents($filename));
$stops=array();
foreach ($snowball as $line)
{
	$word=is_word($line);
	if ($word) $stops[]=$word;
}
return $stops;
}

function is_word($text0)
{
	if (startsWith($text0," ")) return false;
	else return trim(explode("|",$text0)[0]);
}

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

?>
