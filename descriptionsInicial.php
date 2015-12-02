<?php
/*
 * 
 * */
$langs=array("English" => "en", "Spanish" => "es", "Español" => "es");
$path=__DIR__."/courses0/";
$path_target=__DIR__."/descriptions/";
$head1='<!DOCTYPE html><html><head><title>';
$head2='</title><meta charset="utf-8" />
</head><body><!-- MOOC from Edx -->';
$end='
</body></html>';
//echo $path."\n";
$dir = opendir($path);
//echo $dir;
//$limit=0;
while ($item = readdir($dir)){
	
if( $item != "." && $item != ".." && !is_dir($path.$item))
{	
		$doc = new DOMDocument();
		
		if (@$doc->loadHTML(file_get_contents($path.$item)))
		{
			//var_dump($doc);
			$xpath = new DOMXPath($doc);
			$query = '//div[@class="see-more-content"]';
			$descs = $xpath->query($query);
			$description="";
			//echo "\n".$item."\n";
			//foreach($descs as $desc){echo "\n".$desc;}
			//var_dump($descs);
			foreach ($descs as $des) {
				$description="<p>".$des->nodeValue."</p>";
				//echo $description;
			}
		
			$willLearn="";
			
			$query = '//div/*/h3[text()="What you\'ll learn"]/../*/ul';
			
			$competences = $xpath->query($query);
			//var_dump($competences);
			foreach ($competences as $comp) {
				$willLearn=$comp->nodeValue;
				$description.="<h2>You will learn</h2>".$willLearn;
				
			}
			//echo "*************\n".$willLearn."\n***\n";
			
			$title=basename($item, "| edX.html");
			
			$lang=false;
			$query = '//li[@data-field="language"]/span[2]';
			
			$langs0 = $xpath->query($query);
			//var_dump($langs0);
			foreach ($langs0 as $l) 
			{
				//var_dump($l);
				if (array_key_exists(trim($l->textContent),$langs))
				$lang=$langs[trim($l->textContent)];
			}
			
			
			$web=$head1.$title.$head2."<h1>".$title."</h1>".$description.$end;
			if ($lang){
				if (file_put_contents($path_target.$lang."/".$title.".html" ,$web))
					{echo $title." OK\n";}
				else 
					{echo "WARNING ".$title." not save\n";}
			}
		}
		else //if ($doc->loadHTML(file_get_contents($path.$item)))
		{;}
}
else 
{ echo "\nSkip".$path.$item;}
	
	

//if ($limit++>5) break;
}

?>
