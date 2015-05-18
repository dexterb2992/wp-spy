<?php
error_reporting(0);

function pre($str){
	echo "<pre>";
	print_r($str);
	echo "</pre>";
}

function strToAlphanumeric($str){
	return preg_replace("/[^a-zA-Z0-9 -]+/", "", $str);
}

function cleanStr($str){
	return ucwords( str_replace("-", " ", $str) );
}

function filter_numbers($str){
	return filter_var($str, FILTER_SANITIZE_NUMBER_INT);
}

function clean_html($html){
	$dom = new DOMDocument;

	$dom->loadHTML($html);
	$strongs = $dom->getElementsByTagName("strong");

	$flag = false;

	$index = 0;

	foreach ($strongs as $strongs) {
	    $index++;

	    $value = (string) $strongs->getAttribute( 'class' );
	    if ($value == "metrics-data align-vmiddle") {
	       $temp = $strongs->nodeValue;
	    	return $temp;
	    }
	}
}

function capitalize($str){
	return $str = implode(',', array_map('ucfirst', explode(',', $str)));
}

function checkUrl($url){
	$file = $url."/wp-admin/index.php";

	$file_headers = @get_headers($file);
	if($file_headers[0] == 'HTTP/1.1 404 Not Found') {		
	   return 0;
	}
	
	return 1;
}


function validateUrl($url){
	$parse = parse_url($url);
	
	$url = isset($parse['host']) ? $parse['host'] : (isset($parse['path']) ? $parse['path'] : false );

	if($url == false){
		return false;
	}

	$pos = strpos($url, "http://");
	if($pos !== false){
		return $url;
	}
	return "http://".$url;
	
}

function url_exists($url) {
	$parse = parse_url($url);	
	$domain = $parse['host'];
	
	if ( checkdnsrr($domain, 'ANY') ) {
	  return true;
	}

	return false;
}

// function getPageData($url) {
// 	if(function_exists('curl_init')) {
// 		$ch = curl_init($url);
// 		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 		if((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'on')) {
// 			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// 		}
// 		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
// 		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
// 		return @curl_exec($ch);
// 	}else {
// 		return @file_get_contents($this->url);
// 	}
// }

function getPageData($url){

	if(!function_exists('curl_init')){
		return @file_get_contents($url);
	}

	$curl = curl_init();
	$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
	$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	$header[] = "Cache-Control: max-age=0";
	$header[] = "Connection: keep-alive";
	$header[] = "Keep-Alive: 300";
	$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$header[] = "Accept-Language: en-us,en;q=0.5";
	$header[] = "Pragma: ";

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0 Firefox/5.0');
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_REFERER, $url);
	curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
	curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); //CURLOPT_FOLLOWLOCATION Disabled...
	curl_setopt($curl, CURLOPT_TIMEOUT, 60);

	$html = curl_exec($curl);

	$status = curl_getinfo($curl);
	curl_close($curl);

	if($status['http_code']!=200){
	    if($status['http_code'] == 301 || $status['http_code'] == 302) {
	        list($header) = explode("\r\n\r\n", $html, 2);
	        $matches = array();
	        preg_match("/(Location:|URI:)[^(\n)]*/", $header, $matches);
	        $url = trim(str_replace($matches[1],"",$matches[0]));
	        $url_parsed = parse_url($url);
	        return (isset($url_parsed))? getPageData($url):'';
	    }

	    // LOG IF ERRORS EXISTS
	    $oline='';
	    foreach($status as $key=>$eline){
	    	@$oline.='['.$key.']'.$eline.' ';
	    }
	    $line =$oline." \r\n ".$url."\r\n-----------------\r\n";
	    $handle = @fopen('./curl.error.log', 'a');
	    fwrite($handle, $line);
	    return FALSE;
	}
	return $html;
}

function getPageWithProxy($proxy, $url, $referer, $agent, $header, $timeout) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
 
    $result['EXE'] = curl_exec($ch);
    $result['INF'] = curl_getinfo($ch);
    $result['ERR'] = curl_error($ch);
 
    curl_close($ch);
 
    return $result;
}

function windowLocation($location){
	echo "<script>window.location='".$location."';</script>";
}

function get_random_string($valid_chars, $length){
    // start with an empty random string
    $random_string = "";

    // count the number of chars in the valid chars string so we know how many choices we have
    $num_valid_chars = strlen($valid_chars);

    // repeat the steps until we've created a string of the right length
    for ($i = 0; $i < $length; $i++)
    {
        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_valid_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick-1];

        // add the randomly-chosen char onto the end of our string so far
        $random_string .= $random_char;
    }

    // return our finished random string
    return $random_string;
}

function getTextFromNode($Node, $Text = "") { 
	if ( !isset($Node->tagName) ) return $Text.$Node->textContent;
         
    if ($Node->tagName == null) 
        return $Text.$Node->textContent; 

    $Node = $Node->firstChild; 
    if ($Node != null) 
        $Text = getTextFromNode($Node, $Text); 

    while($Node->nextSibling != null) { 
        $Text = getTextFromNode($Node->nextSibling, $Text); 
        $Node = $Node->nextSibling; 
    } 
    return $Text; 
} 

function if_file_exists($url) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);   
	curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if (($code == 301) || ($code == 302) || ($code == 404)) {
	  	//This was a redirect
		return false;
	}
	return true;
}

function DOMinnerHTML(DOMNode $element, $strip_tags = false) { 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child){ 
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    if( $strip_tags == true ){
    	return strip_tags($innerHTML); 
    }
    return $innerHTML; 
} 

function limitString($string, $limit = 255) {
    // Return early if the string is already shorter than the limit
    if(strlen($string) < $limit) {return $string;}

    $regex = "/(.{1,$limit})\b/";
    preg_match($regex, $string, $matches);
    return $matches[1]."...";
}

function getLinks($domain){
	$internal = array();
	$external = array();
	$html = getPageData($domain);

	libxml_use_internal_errors(true);

	$dom = new DOMDocument;

	@$dom->loadHTML($html);

	$anchors = $dom->getElementsByTagName("a");

	$nfollow_internal = 0;
	$nfollow_external = 0;
	$url_parsed = parse_url($domain);
	// extract internal and external links count
		foreach ($anchors as $anchor) {
			$href = $anchor->getAttribute("href");
			$parse_url = parse_url($href);
			
			if( isset($parse_url["host"]) ){
				if( $url_parsed["host"] == $parse_url["host"] ){
					array_push($internal, array("url" => $href, "text" => DOMinnerHTML($anchor)));
					if( $anchor->getAttribute("rel") == "nofollow" ){
						$nfollow_internal++;
					}
				}else{
					array_push($external, array("url" => $href, "text" => DOMinnerHTML($anchor)));
					if( $anchor->getAttribute("rel") == "nofollow" ){
						$nfollow_external++;
					}
				}
			}
		}

	return array("internal_links" => array("nofollow" => $nfollow_internal, "links" => $internal), 
				"external_links" =>array("nofollow" => $nfollow_external, "links" => $external) );
}

function roundk($number){
	$number = (double)(str_replace(",", "", $number));
	if ($number > 999 && $number <= 999999) {
		$sep = ($number > 1000)? '+' : '';
	    return $result = floor($number / 1000) . 'K'.$sep;
	}elseif ($number > 999999) {
		$sep = ($number > 1000000)? '+' : '';
	    return $result = floor($number / 1000000) . 'M'.$sep;
	}else {
	    return $result = number_format($number);
	}
}

function get_sites(){
	global $wpdb;
	global $fn;
	$table_name = $GLOBALS['CFG']['Database']['prefix'].'wpspy_activity_log';

	$sql = "SELECT DISTINCT url FROM ".$table_name;
	$res = $fn->fetch( $sql, false );
	$sites = array();
	$row = $fn->numrows($sql);
	if( $row > 0 ){
		foreach ($res as $key) {
			if( validateUrl($key["url"]) ) {
				array_push($sites, $key["url"]);
			}
		}
	}
	return $sites;
}