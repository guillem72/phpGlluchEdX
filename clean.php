<?php

clean_all();

function clean_puntuation($value)
{
	$bads=array("  Fz","; Fx","# Fz","- Fg","( Fpa", ") Fpt","? Fit",": Fd","\/ Fh","… Fz","* Fz","\" Fe","“ Fz","– Fz","! Fat","¿ Fia",);
		if(in_array($value,$bads)) return false;
		else return $value;
}



function clean_all()
{
$path=__DIR__."/json1/";
$path_target=__DIR__."/json2/";
$dir = opendir($path);

while ($item = readdir($dir))
{
	if( $item != "." && $item != ".." && !is_dir($path.$item))
	{
		$info0=file_get_contents($path.$item);
		$info=json_decode($info0);
		//var_dump($info);
		
		if(isset($info->description_tokens) AND $info->description_tokens) 
		{
			$info->description_tokens=array_filter($info->description_tokens,"clean_puntuation");
		}
		
		$requi2="";
		if(isset($info->requisite_tokens) AND $info->requisite_tokens) 
		{
			$info->requisite_tokens=array_filter($info->requisite_tokens,"clean_puntuation");
		}
		
		
		file_put_contents($path_target.$item,json_encode($info, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	}
	//if ($limit++>5) break;
}
}

?>
