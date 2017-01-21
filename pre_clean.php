<?php

clean_all();

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function clean_all()
{
	
$path=__DIR__."/json0/";
$path_target=__DIR__."/json0/pre/";
$dir = opendir($path);
$limit=0;
while ($item = readdir($dir))
{
	if( $item != "." && $item != ".." && !is_dir($path.$item))
	{
		$info0=file_get_contents($path.$item);
		$info=json_decode($info0);
		//var_dump($info);
		
		if (isset($info->description ) AND $info->description!="")
		{
			
			if(is_array($info->description)) 
				$string=implode(" ",$info->description);
			else	$string = $info->description;
			
			if (startsWith($string,"This is an Archived Course"))
				{
			//echo "\n****Original*****\n".$string;
				$string=substr($string,281);
			//echo "\n*********Resulta en**************\n".$string;
			}
		$info->description=$string;
		}
		else echo "\nWARNING ".$item." description empty";
		
		if (isset ($info->url) AND !startsWith($info->url,"https://"))
			$info->url="https://www.edx.org".$info->url;
		
		file_put_contents($path_target.$item,json_encode($info, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	}
	//if ($limit++>8) break;
}
}

?>
