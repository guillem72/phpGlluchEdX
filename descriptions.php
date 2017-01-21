<?php
require_once  __DIR__."/course.class.php";


$quitar=array
//("description" => "About this course",
("description" => false,
//"contents" => false,
"instructors" => false,
"requirements" =>"Prerequisites",
"length" => false,
"effort" => false,
"prices" => false,
"institutions" => false,
"subjects" => false,
"language" => false,
"url" => false,
"title" => false,
"keywords" => false
);

$quitar_donde=array(
"description" => false,
"contents" => false,
"instructors" => false,
"requirements" =>"beginning",
"length" => false,
"effort" => false,
"prices" => false,
"institutions" => false,
"subjects" => false,
"language" => false,
"url" => false,
"title" => false,
"keywords" => false
);

$query=array(
"description" => '//div[@class="course-description wysiwyg-content"]/div[@class="see-more-content"] | //div[@class="course-info-list wysiwyg-content"] | //section[@class]/div[@class="overview"] | //div[@class="subtitle"]',
"contents" => '//div[@class="syllabus-content animate-wrapper wysiwyg-content"]',
"instructors" => '//p[@class="instructor-name"]',
"requirements" => '//h2[@class="course-info-heading reg"]/..',
"length" => '//li[@data-field="length"]/span[@class="block-list__desc"]',
"effort" => '//li[@data-field="effort"]/span[@class="block-list__desc"]',
"prices" => '//li[@data-field="price"]/span[@class="block-list__desc"]',
"institutions" => '//li[@data-field="school"]/span[@class="block-list__desc"]',
"subjects" => '//li[@data-field="subject"]/span[@class="block-list__desc"]',
"language" => '//li[@data-field="language"]/span[@class="block-list__desc"]',
"url" => '//link[@rel="canonical"]/@href',
"title" => '//meta[@property="og:title"]/@content',
"keywords" => false
);


$no_xpath=array();

//protected $params=array("description","contents","instructors","requirements", "length","effort","prices","institutions","subjects","language","url","title","keywords");


$path=__DIR__."/courses/";
$path_target=__DIR__."/json0/";
//echo $path."\n";
$dir = opendir($path);
//echo $dir;


Course::resetLog();
while ($item = readdir($dir)){
	
if( $item != "." && $item != ".." && !is_dir($path.$item))
{	
	$edx=new Course($path.$item);
	$edx->quitar=$quitar;
	$edx->quitar_donde=$quitar_donde;
	$edx->query=$query;
	$edx->no_xpath=$no_xpath;
	$filename2=trim(basename($item," .html"));
	$edx->get_info_course();
	//if ($edx->query["language"]) $filename2=$edx->processed["language"]."/".$filename2;
	$edx->save($path_target.$filename2.".json");	
}
else 
{ echo "\nSkip".$path.$item;}
	
	

//if ($limit++>5) break;
}

?>
