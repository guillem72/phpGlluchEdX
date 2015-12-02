<?php

 
require_once('html2text.php');

class Course {

public static $log_file="course.log";
public $debug=true;
public $no_xpath=array("requirements","length");
public $file_target=false;
public $processed=false;
protected $params=array("description","contents","instructors","requirements", "length","effort","prices","institutions","subjects","language","url","title","keywords");

public $quitar=array
("description" => "Descripción de curso",
"contents" => "/(Módulo|Modulo) [0-9]* *(\.|:) /",
"instructors" => false,
"requirements" =>false,
"length" => false,
"effort" => false,
"prices" => false,
"institutions" => false,
"subjects" => false,
"language" => false,
"url" => false,
"title" => "Miriada X -",
"keywords" => false
);

public $quitar_donde=array(
"description" => "beginning",
"contents" => "each",
"instructors" => false,
"requirements" =>false,
"length" => false,
"effort" => false,
"prices" => false,
"institutions" => false,
"subjects" => false,
"language" => false,
"url" => false,
"title" => "beginning",
"keywords" => false
);

public $query=array(
"description" => '//div[@id="p_p_id_coursedescription_WAR_liferaylmsportlet_"]',
"contents" => '//table[@id="idModuleTable"]/tr/td[@class="title"]',
"instructors" => '//div[@class="teacher"]//span[@class="user-name"]',
"requirements" => array("** Conocimientos **", "** Profesores **"),
"length" => array("** Duración **","** Fecha de inicio **"),
"effort" => false,
"prices" => false,
"institutions" => '//div[@class="university"]//img/@title',
"subjects" => false,
"language" => false,
"url" => '//meta[@property="og:url"]/@content',
"title" => '//head/title',
"keywords" => '//meta[@name="keywords"]/@content'
);

private $doc; //$this->doc
//private $test_param=false;

private $doc_text;
public $filename;//with path

function  __construct($nameOfFile)
{
	$this->filename=$nameOfFile;
	$this->doc=new DOMDocument();
	@$this->doc->loadHTMLFile($this->filename);
	@$this->doc_text=html2text( $nameOfFile, false, true );
	if ($this->debug) file_put_contents(self::$log_file,"\n--->File: ".$this->filename."\t".date("c")."\n",FILE_APPEND);
	echo "\n--->File: ".$this->filename;
}


public static function resetLog()
{
	unlink(self::$log_file);
}


public function save($destination=false)
{
	if (!$this->processed) $this->get_info_course();
	if (!$destination AND !$this->file_target)
		return false; //TODO warning
	if ($destination) $this->file_target=$destination;
	file_put_contents($this->file_target,json_encode($this->processed, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));	
}

public function get_info_course()
{
	if ($this->processed) return $this->processed;
	$devolver=array();
	$val=false;
	foreach ($this->params as $param)
	{
		echo "\nparam=".$param."\t";
		if (in_array($param,$this->no_xpath))
		$val=$this->get_part_noxpath($param);
		
		else $val=$this->get_part($param,$this->doc);
		var_dump($val);
		if ($val) 
		{
			$devolver[$param]=$val;
		}
		if ($val AND $this->quitar_donde[$param]=="end") 
		{
			$devolver[$param]=trim(explode($this->quitar[$param],$val)[0]);
		}
		else
		{
			if ($val AND $this->quitar_donde[$param]=="beginning" ) 
			$devolver[$param]=trim(explode($this->quitar[$param],$val)[1]);
		}
		
	}
	if (isset($devolver["keywords"])) $devolver["keywords"]=array_map('trim',explode(",",$devolver["keywords"]));
	$this->processed=$devolver;
	return $devolver;
}

protected function get_part($part,$doc)
{
	$xpath = new DOMXPath($doc);
	
	
	if (!isset($this->query[$part]) OR !$this->query[$part]  ) 
	{
		if ($this->debug) file_put_contents(self::$log_file," No query for ".$part."\tCodeLine=".__LINE__,FILE_APPEND);
		return false;//there isn't a defined query for this element
	}
	
	$items = $xpath->query($this->query[$part]);
	
	
	$valor=false;
	if (!$items) {
	if ($this->debug) file_put_contents(self::$log_file," No response for ".$part."\t".__LINE__,FILE_APPEND);
	return false;}
	foreach ($items as $item) 
	{
		if ($this->quitar_donde[$part]=="each")
		{
			
			$valor[]=trim(preg_replace($this->quitar[$part],"",$item->nodeValue,1));
		}
		else
		{
			$valor[]=trim($item->nodeValue);
		}
	}
	if($valor)
	{
		if (count($valor)==1)//the result has only one element
		{
			$valor=trim($valor[0]);
		}		
	}
		
	//return $description;
	return $valor;
}


protected function get_part_noxpath($part)
{
	//var_dump($this->query[$part]);
	//echo "\nPos1".strrpos ($this->doc_text,$this->query[$part][0]);
	$pos1=strrpos ($this->doc_text,$this->query[$part][0])+strlen($this->query[$part][0]);
	//echo "\nPos2".strrpos ($this->doc_text,$this->query[$part][1]);
	$pos2=strrpos ($this->doc_text,$this->query[$part][1])-$pos1;
	if ($pos1 AND $pos2) return trim(substr($this->doc_text,$pos1,$pos2));
	else return false;
}


}//end of class
?>
