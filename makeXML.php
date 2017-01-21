<?php
//var_dump($argv);

$doc=new DOMDocument();
@$doc->loadHTMLFile($argv[1]);
$doc->save($argv[2]);


?>
