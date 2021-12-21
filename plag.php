 <?php
//$data = isset($_GET['text'])?$_GET['text']:'';
function pluug($text){           
require 'simple_html_dom.php';

$data = $text;
$not_printed = true;
$data_arr = explode(PHP_EOL, $data);
for($i = 0; $i < count($data_arr) ; $i++) {
    $data_arr[$i] = trim($data_arr[$i]);
}
$data_arr = array_filter($data_arr);
$totalSentences = 0;
$plagUrls = [];
$plagCount = 0;
foreach($data_arr as $line) {
    $line = explode(". ",$line);
    for($i = 0; $i < count($line) ; $i++) {
        $line[$i] = trim($line[$i], ' ";.,“”');
        $line[$i] = trim($line[$i], "'");
    }
    $line = array_filter($line);
    if(count($line) > 0 && $not_printed) {
        echo "<div class = 'results' id='result'><h4>Results</h4>";
        $not_printed = false;
    }

    
    
    foreach($line as $sentence) {
        if(strlen($sentence) < 2) {
            continue;
        }
        $totalSentences++;
        $s1 = "";
        $n = strlen($sentence);
        $sentence = str_replace('“','"',$sentence);
        $sentence = str_replace('”','"',$sentence);
        $sentence = str_replace('‘',"'",$sentence);
        $sentence = str_replace('’',"'",$sentence);

        $s1 = "";
        $n = strlen($sentence);
        for($i = 0; $i < $n; $i++) {
            // echo $sentence[$i]."<br>";
            if($sentence[$i] == ' ' 
                && $i + 1 < $n 
                && ($sentence[$i+1] != '.')
                && ($sentence[$i+1] != '"')
                && ($sentence[$i+1] != "'")
                && !($sentence[$i+1] >= 0 && $sentence[$i+1] <= 9 )
                && !($sentence[$i+1] >= 'a' && $sentence[$i+1] <= 'z') 
                && !($sentence[$i+1] >= 'A' && $sentence[$i+1] <= 'Z')) {
                continue;
            }
            if($sentence[$i] != '.' 
                && $sentence[$i] != ' '
                && ($sentence[$i] != '"')
                && ($sentence[$i] != "'")
                && !($sentence[$i] >= 0 && $sentence[$i] <= 9 )
                && !($sentence[$i] >= 'a' && $sentence[$i] <= 'z') 
                && !($sentence[$i] >= 'A' && $sentence[$i] <= 'Z')) {
                
                if($i + 1 < $n && $sentence[$i + 1] != ' ') {
                    $s1 .= $sentence[$i].' ';
                }
                else {
                    $s1 .= $sentence[$i];
                }

            }
            else {
                $s1 .= $sentence[$i];
            }
            
        }
        $sentence = $s1;
        $query = urlencode($sentence);
        $url = 'https://www.google.com/search?q='.$query;
        // echo $url;
        $result = file_get_html($url);
        $found = false;
        foreach($result->find('#main div .ZINbbc.xpd.O9g5cc.uUPGi') as $entry) {
            //echo $entry;
            $text = $entry->find('.kCrYT div .BNeawe.s3v9rd.AP7Wnd div div (.BNeawe.s3v9rd.AP7Wnd)');
            //print_r( $text);
            $text = $text[0]->plaintext;
            if(strlen($text) < 2) {
                continue;
            }
            $text = str_replace('&#8220;','"',$text);
            $text = str_replace('&#8221;','"',$text);
            $text = str_replace('&#8216;',"'",$text);
            $text = str_replace('&#8217;',"'",$text);
            $s1 = "";
            $n = strlen($text);
            for($i = 0; $i < $n; $i++) {
                if($text[$i] == ' '
                    && $i + 1 < $n 
                    && ($text[$i+1]!= '.')  
                    && ($text[$i+1] != '"')
                    && ($text[$i+1] != "'")
                    && !($text[$i+1] >= 0 && $text[$i+1] <= 9 )
                    && !($text[$i+1] >= 'a' && $text[$i+1] <= 'z') 
                    && !($text[$i+1] >= 'A' && $text[$i+1] <= 'Z')) {
                    continue;
                }
                if($text[$i] != '.' 
                    && $text[$i] != ' '
                    && ($text[$i] != '"')
                    && ($text[$i] != "'")
                    && !($text[$i] >= 0 && $text[$i] <= 9 ) 
                    && !($text[$i] >= 'a' && $text[$i] <= 'z') 
                    && !($text[$i] >= 'A' && $text[$i] <= 'Z')) {
                    
                    if($i + 1 < $n && $text[$i + 1] != ' ') {
                        $s1 .= $text[$i].' ';
                    }
                    else {
                        $s1 .= $text[$i];
                    }
    
                }
                else {
                    $s1 .= $text[$i];
                }
                
            }
            $text = $s1;

            $urlstring = $entry->find('.kCrYT a');
            $urlstring = $urlstring[0]->href;
            $pattern = "/q=(http|https):\/\/(.+?)\//";
            preg_match($pattern, $urlstring, $matches);
            $urlstring = $matches[2];
            // echo "$urlstring<br>$text<br><br>";
            // $arr[$urlstring] = $text;
            if (strpos($text, $sentence) !== false) {
                $plagCount++;
                $plagUrls[$urlstring]++;
                echo "<div class = 'sent'>
                        <div class = 'plag'>plagiarised</div>$sentence - <strong>$urlstring</strong>
                    </div><br>";
                $found = true;
                break;
            }
        }
        if($found === false) {
            $url = 'https://www.google.com/search?q='.$query;
            $url .= '&cr=countryIN';
            $result = file_get_html($url);
            foreach($result->find('#main div .ZINbbc.xpd.O9g5cc.uUPGi') as $entry) {
                // echo $entry;
                $text = $entry->find('.kCrYT div .BNeawe.s3v9rd.AP7Wnd div div (.BNeawe.s3v9rd.AP7Wnd)');
                $text = $text[0]->plaintext;
                if(strlen($text) < 2) {
                    continue;
                }
                $text = str_replace('&#8220;','"',$text);
                $text = str_replace('&#8221;','"',$text);
                $text = str_replace('&#8216;',"'",$text);
                $text = str_replace('&#8217;',"'",$text);
                $s1 = "";
                $n = strlen($text);
                for($i = 0; $i < $n; $i++) {
                    if($text[$i] == ' '
                        && $i + 1 < $n 
                        && ($text[$i+1]!= '.')  
                        && ($text[$i+1] != '"')
                        && ($text[$i+1] != "'")
                        && !($text[$i+1] >= 0 && $text[$i+1] <= 9 )
                        && !($text[$i+1] >= 'a' && $text[$i+1] <= 'z') 
                        && !($text[$i+1] >= 'A' && $text[$i+1] <= 'Z')) {
                        continue;
                    }
                    if($text[$i] != '.' 
                        && $text[$i] != ' '
                        && ($text[$i] != '"')
                        && ($text[$i] != "'")
                        && !($text[$i] >= 0 && $text[$i] <= 9 ) 
                        && !($text[$i] >= 'a' && $text[$i] <= 'z') 
                        && !($text[$i] >= 'A' && $text[$i] <= 'Z')) {
                        
                        if($i + 1 < $n && $text[$i + 1] != ' ') {
                            $s1 .= $text[$i].' ';
                        }
                        else {
                            $s1 .= $text[$i];
                        }
        
                    }
                    else {
                        $s1 .= $text[$i];
                    }
                    
                }
                $text = $s1;

                $urlstring = $entry->find('.kCrYT a');
                $urlstring = $urlstring[0]->href;
                $pattern = "/q=(http|https):\/\/(.+?)\//";
                preg_match($pattern, $urlstring, $matches);
                $urlstring = $matches[2];
                // echo "$urlstring<br>$text<br><br>";
                // $arr[$urlstring] = $text;
                if (strpos($text, $sentence) !== false) {
                    $plagCount++;
                    $plagUrls[$urlstring]++;
                    echo "<div class = 'sent'>
                            <div class = 'plag'>plagiarised</div>$sentence - <strong>$urlstring</strong>
                        </div><br>";
                    $found = true;
                    break;
                }
    
            }
            if($found === false) {
                echo "<div class = 'sent'>
                            <div class = 'uni'>unique</div>$sentence
                        </div><br>";
            }
        }
    }
}


$plagPercent = ($plagCount / $totalSentences) * 100;
$unPercent = 100 - $plagPercent;
$my = array($plagPercent,$unPercent);
return $my;

}
/*function plagPercent($plagPercent){
    return $plagPercent;
}
function unPercent($unPercent){
    return $unPercent
}*/
?>


