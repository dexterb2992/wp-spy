<?php
// this file requires simple_html_dom.php

include "social.config.php";


class SocialStat{
	function google_plus_counter( $url, $with_session = true){		
		$raw_url = $url;
		$url = "https://plusone.google.com/u/0/_/+1/fastbutton?url=".urlencode($url)."&count=true";
		$content = @file_get_contents($url);
			$dom = new DOMDocument;
			$dom->preserveWhiteSpace = false;
			@$dom->loadHTML($content);
			$domxpath = new DOMXPath($dom);
			$newDom = new DOMDocument;
			$newDom->formatOutput = true;

			$filtered = $domxpath->query("//div[@id='aggregateCount']");

			if( count( $filtered ) == 0 ){
				return 0;
			}

			if( $with_session === false ){
				return $filtered->item(0)->nodeValue;
			}

			$_SESSION[$raw_url]['social']['google_plus'] = $filtered->item(0)->nodeValue;
			return $_SESSION[$raw_url]['social']['google_plus'];

		return 0;

	}

	function gplus_counter( $url, $with_session = true ){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.rawurldecode($url).'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		$curl_results = curl_exec ($curl);
		curl_close ($curl);
		$json = json_decode($curl_results, true);
		return isset($json[0]['result']['metadata']['globalCounts']['count'])?intval( $json[0]['result']['metadata']['globalCounts']['count'] ):0;
	}

	function twitter_counter( $url , $with_session = true){
		$raw_url = $url;
		$url = "http://urls.api.twitter.com/1/urls/count.json?url=".urlencode(preg_replace("#https?\:\/\/#","",$url));

		$ch = curl_init();

		$options = array(
			CURLOPT_RETURNTRANSFER => true,	 // return web page
			CURLOPT_HEADER	 => false,	// don't return headers
			CURLOPT_FOLLOWLOCATION => true,	 // follow redirects
			CURLOPT_ENCODING	 => "",	 // handle all encodings
			CURLOPT_USERAGENT	 => 'spider', // who am i
			CURLOPT_AUTOREFERER	=> true,	 // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 3,	 // timeout on connect
			CURLOPT_TIMEOUT	 => 1,	 // timeout on response
			CURLOPT_MAXREDIRS	 => 3,	 // stop after 10 redirects
			CURLOPT_URL	 => $url,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => false
		);

		curl_setopt_array($ch, $options);

		$responseJSON = curl_exec($ch);

		curl_close($ch);
		
			if( empty( $responseJSON ) ) {
				return 0;
			}

		$response = @json_decode( $responseJSON, true );

		if( empty($response) || !isset( $response["count"] ) ){
			return 0;
		}

		if( $with_session === false ){
			return $response['count'];
		}

		$_SESSION[$raw_url]['social']['twitter'] = $response['count'];
		return $_SESSION[$raw_url]['social']['twitter'];
	}


	function facebook_counter($url, $with_session = true){
		$config = new SocialConfig();
		$cfg = $config->FBConfig();

		$raw_url = $url;

		$action="https://graph.facebook.com/oauth/access_token?client_id=".$cfg['APPID']."&client_secret=".$cfg['APPSECRET']."&grant_type=client_credentials" ;

		$html = @file_get_contents($action);
		$access_token=substr($html,13); //we're all good
		

		$url1 = "https://graph.facebook.com/".$url."?access_token=".$access_token;
		
		$data = file_get_contents($url1);
		
		$data = json_decode($data);

		if( $with_session === false ){
			return $data->share->share_count;
		}

		$_SESSION[$raw_url]['social']['facebook'] = $data->share->share_count;

		return $_SESSION[$raw_url]['social']['facebook'];

	}


	//get linkedin count result/cUrl
	function linkedin_counter( $url , $with_session = true ){
		
		$raw_url = $url;

		$url = "http://www.linkedin.com/countserv/count/share?url=".$url."&lang=en_US&callback=response&format=json";
		
		$responseJSON = @file_get_contents($url);

		if( empty( $responseJSON ) ){
			return 0;
		}
		$response = json_decode($responseJSON);

		
		if( $with_session === false ){
			return $response->count;
		}

		if( !isset( $response->count ) ){
			$_SESSION[$raw_url]['social']['linkedin'] = 0;
			return 0;
		}
		
		$_SESSION[$raw_url]['social']['linkedin'] = $response->count;

		return $_SESSION[$raw_url]['social']['linkedin'];

	}

	//get pinterest count result/cUrl
	function pinterest_counter( $url , $with_session = true ){
		$raw_url = $url;

		$url = "http://api.pinterest.com/v1/urls/count.json?callback=response&url=".urlencode($url);

		$ch = curl_init();

		$options = array(
			CURLOPT_RETURNTRANSFER => true,	 // return web page
			CURLOPT_HEADER	 => false,	// don't return headers
			CURLOPT_FOLLOWLOCATION => true,	 // follow redirects
			CURLOPT_ENCODING	 => "",	 // handle all encodings
			CURLOPT_USERAGENT	 => 'spider', // who am i
			CURLOPT_AUTOREFERER	=> true,	 // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 5,	 // timeout on connect
			CURLOPT_TIMEOUT	 => 10,	 // timeout on response
			CURLOPT_MAXREDIRS	 => 3,	 // stop after 10 redirects
			CURLOPT_URL	 => $url,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => false
		);

		curl_setopt_array($ch, $options);

		$responseJSON = curl_exec($ch);

		curl_close($ch);

		if( empty( $responseJSON ) ){
			return 0;
		}

		$responseJSON = preg_replace('/^response\((.*)\)$/', "\\1", $responseJSON);

		$response = @json_decode( $responseJSON, true );


		if( !isset( $response['count'] )){
			return 0;
		}

		if( $with_session === false ){
			return $response['count'];
		}


		$_SESSION[$raw_url]['social']['pinterest'] = $response['count'];

		return $_SESSION[$raw_url]['social']['pinterest'];
	}

	//get stumbleupon count result/cUrl
	function stumbleupon_counter( $url, $with_session = true ){
		$raw_url = $url;
		
		$url = "http://www.stumbleupon.com/services/1.01/badge.getinfo?url=".$url;

		$ch = curl_init();

		$options = array(
			CURLOPT_RETURNTRANSFER => true,	 // return web page
			CURLOPT_HEADER	 => false,	// don't return headers
			CURLOPT_FOLLOWLOCATION => true,	 // follow redirects
			CURLOPT_ENCODING	 => "",	 // handle all encodings
			CURLOPT_USERAGENT	 => 'spider', // who am i
			CURLOPT_AUTOREFERER	=> true,	 // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 3,	 // timeout on connect
			CURLOPT_TIMEOUT	 => 1,	 // timeout on response
			CURLOPT_MAXREDIRS	 => 3,	 // stop after 10 redirects
			CURLOPT_URL	 => $url,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => false
		);

		curl_setopt_array($ch, $options);
		
		$responseJSON = curl_exec($ch);

		curl_close($ch);

		$response = json_decode( $responseJSON, true );
		
		if( !isset( $response['result'] ) || !isset( $response['result']['views']) ){
			return 0;
		}

		if( $with_session === false ){
			return $response['result']['views'];
		}

		$_SESSION[$raw_url]['social']['stumbleupon'] = $response['result']['views'];

		return $_SESSION[$raw_url]['social']['stumbleupon'];
	}

	// get delicious count
	function delicious_counter( $url, $with_session = true ){
		return 'N/A';
	}


	// get social mention data
	function social_mention( $key, $with_session = true ){
		try{
			$html = file_get_html("http://socialmention.com/search?t=all&q=".urlencode($key)."&btnG=Search");
		
			$results = array();
			$ids = array("score_strength", "score_sentiment", "score_passion", "score_reach");

			foreach ($ids as $id => $value) {
				foreach($html->find('div#'.$value) as $element) {
				    $results[$value] = $element->find('div.score', 0)->plaintext;
				}
			}

			foreach($html->find('div#top_keywords') as $element) {
			    $title =$element->find('h4.qtip', 0)->plaintext;
			    $title = str_replace( " ", "_", strtolower($title) );
			    
			    $vals = array("sentiment", "top_keywords");

			    if( in_array($title, $vals) ){
			    	$table = $element->find('table', 0);

					foreach($table->find('tr') as $tr) {
						$col = $tr->find('td', 0)->plaintext;
						
						$results[$title][$col] = $tr->find('td', 2)->plaintext;
				    }
			    }
			}

			return json_encode($results);
		}catch(Exception $e){
			return  array("score_strength" => "-", "score_sentiment" => "-", 
				"score_passion" => "-", "score_reach" => "-");

		}
	}

	//  get social mention links
	function social_mention_links( $key, $with_session = true ){
		$json = file_get_html("http://api2.socialmention.com/search?q=".urlencode($key)."&t=all&f=json");
		echo $json;
	}
}