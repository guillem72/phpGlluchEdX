<?php
include("analyzer.php");
include("stopwords.php");


//echo choose23("comunicación comunicación NCFS000 1");
//echo choose23("tipos tipo NCMP000 1");
normalize_all();

function normalize($texto)
{
    $analizador = new analyzer("192.168.12.62:2002");
    //var_dump($texto);
    $output = $analizador->analyze_text($texto);
    //echo $output;
    $tokens = explode("\n", $output);
    $toks = array_filter(array_map("choose23", $tokens), "exists");
    return stopwords($toks);
}

function exists($val)
{
    if (isset($val) AND $val != null AND $val != "") return $val;
    else return false;
}

function stopwords($strings)
{
    $stops = get_stops();
    $val = array();
    foreach ($strings as $string) {
        $word = trim(explode(" ", $string)[0]);
        if (!in_array($word, $stops)) $val[] = $string;
    }
    return $val;
}


function choose23($text0)
{
    if (startsWith($text0, ".") OR startsWith($text0, ",") OR startsWith($text0, " ")) return false;
    $parts = array_map("trim", explode(" ", $text0));
    if (count($parts) < 2) {
        return false;
    }
    return $parts[1] . " " . $parts[2];
}

function normalize_all()
{


    $path = __DIR__ . "/json0/en/";
    $path_target = __DIR__ . "/json1/en/";
    $dir = opendir($path);
    $limit = 0;

    $description_fields = array("description", "contents", "subjects", "title", "keywords");
    $requisite_fields = array("requirements");
    $limit = 0;
    while ($item = readdir($dir)) {
        if ($item != "." && $item != ".." && !is_dir($path . $item)) {
            $info0 = file_get_contents($path . $item);
            echo "\nWorking with " . $item;
            //var_dump($info0);
            $info = json_decode($info0);
            //var_dump($info);
            $des2 = "";
            foreach ($description_fields as $desc) {
                if (isset($info->$desc) AND $info->$desc) {
                    if (is_array($info->$desc)) {

                        $des2 .= implode(" ", $info->$desc);
                    } else $des2 .= " " . $info->$desc . " ";
                }
            }
            $requi2 = "";
            foreach ($requisite_fields as $requi) {
                if (isset($info->$requi) AND $info->$requi) {
                    if (is_array($info->$requi)) {

                        $requi2 .= implode(" ", $info->$requi);
                    } else $requi2 .= " " . $info->$requi . " ";
                }
            }
            $info->description_tokens = normalize($des2);
            //var_dump($info);
            $info->requisite_tokens = normalize($requi2);
            //file_put_contents("log/".$item,$des2);
            file_put_contents($path_target . $item, json_encode($info, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
        //if ($limit++>5) break;
    }
}

?>
