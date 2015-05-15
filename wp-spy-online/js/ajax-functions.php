<?php

function get_sites_json(){
	global $wpdb;
	global $fn;
	$table_name = $GLOBALS['CFG']['Database']['prefix'].'wpspy_activity_log';

	$sql = "SELECT DISTINCT url FROM ".$table_name." ORDER BY url ASC";
	$res = $fn->fetch( $sql, false );

	echo json_encode($res);
}

function store_activity($url, $wpspy_activity) {
    global $wpdb;
    global $fn;
    
    $table_name = $GLOBALS['CFG']['Database']['prefix'].'wpspy_activity_log';
    $date_now = date("Y-m-d H:i:s");
    $sql = "SELECT id, activity_date FROM ".$table_name." WHERE (url='$url') AND (DATE_FORMAT(activity_date,'%Y-%m-%d') = DATE_FORMAT('$date_now', '%Y-%m-%d')) ORDER BY id DESC LIMIT 1";
	$res = $fn->fetch($sql);

   	if( $res != 0 && !empty($res) ){
   		// Update old record in the same day
   			$wpspy_activity["activity_date"] = $date_now;
	        $where = array('id' => $res[0]->id);
	        if( $fn->update( $table_name, $wpspy_activity, $where ) ){
	        	echo json_encode(array("status_code"=>"200", "msg" => "Success!"));
	        }else{
		    	echo json_encode(array("status_code"=>"500", "msg" => "Sorry, something went wrong. Please try again later.", "error" =>  $wpdb->print_error(), "more_info" => $wpdb->last_query));
	        }
   	}else{
   		// Insert new record
	   		$wpspy_activity["activity_date"] = $date_now;

		    if( $fn->insert( $table_name, $wpspy_activity ) ){
		    	echo json_encode(array("status_code"=>"200", "msg" => "Success!"));
		    }else{
		    	echo json_encode(array("status_code"=>"500", "msg" => "Sorry, something went wrong. Please try again later.", "error" =>  $wpdb->print_error(), "more_info" => $wpdb->last_query));
		    }
   	}
}

function get_alexa_rank($url){
	global $wpdb;
	global $fn;
	$table_name = $GLOBALS['CFG']['Database']['prefix'].'wpspy_activity_log';

	$sql = "SELECT alexa_rank, activity_date FROM ".$table_name." WHERE url='".$url."/' ORDER BY activity_date ASC";
	$res = $fn->fetch( $sql, false );
	
	$date = array();
	$alexa_rank = array();
	foreach ($res as $r) {
		$d = strtotime( $r->activity_date );
		array_push( $date, array("label" => date( 'M j, Y, g:i a', $d )) );
		array_push( $alexa_rank, array("value" => (int) str_replace(",", "", $r->alexa_rank)) );
	}
	// echo json_encode(array("dates"=>$date, "alexa_ranks"=>$alexa_rank));
	echo json_encode(array("dates"=>$date, "alexa_ranks"=>$alexa_rank));
}

function get_chart_data($url, $column){
	global $wpdb;
	global $fn;
	$table_name = $GLOBALS['CFG']['Database']['prefix'].'wpspy_activity_log';

	$sql = "SELECT $column, activity_date FROM ".$table_name." WHERE url='".$url."' ORDER BY activity_date ASC";
	// echo $sql;
	$res = $fn->fetch( $sql, false );
	
	$date = array();
	$values = array();
	foreach ($res as $r) {
		$d = strtotime( $r->activity_date );
		$val = (int) str_replace(",", "", $r->$column);
		array_push( $date, array("label" => date( 'M j, Y, g:i a', $d )) );
		array_push( $values, array("value" => $val) );
	}

	echo json_encode(array("dates"=> json_encode($date), "values"=>json_encode($values)));
}

function get_social_mention_links($domain){
	$url = WPSPY_HOST.'data.php?q=get_social_mention_links&domain='.urlencode('"'.$domain.'"');
	$data = getPageData($url);
	echo $data;
}


function get_whois($domain){
	echo $data = getPageData(WPSPY_HOST."data.php?q=get_whois&domain=".$domain);
}

function get_onsite($domain){
	echo $data = getPageData(WPSPY_HOST."data.php?q=get_onsite&domain=".$domain);
}

function get_wordpress_data($domain){
	echo $data = getPageData(WPSPY_HOST."data.php?q=get_wordpress_data&domain=".$domain);
}

function get_links_on_page($domain){
	echo $data = getPageData($domain);
}

function get_page_info($domain){
	echo $data = getPageData(WPSPY_HOST."data.php?q=get_page_info&domain=".$domain);
}

function get_seo_stats($domain){
	echo $data = getPageData(WPSPY_HOST."data.php?q=get_seo_stats&domain=".$domain."&format=json");
}

function get_ie_links($domain){
	$links = getLinks($domain);
	echo json_encode($links);
}

function get_social_stats($domain){
	echo $data = getPageData(WPSPY_HOST."data.php?q=get_social_stats&domain=".$domain."&format=json");
}

function get_social_mention($domain){
	echo $data = getPageData(WPSPY_HOST."data.php?q=get_social_mention&domain=".$domain."&format=json");
}

function get_sitemetrics($domain){
	include "../classes/dbhelper.php";
	$site_metrics = get_site_metrics($domain);
	$alexa_rank_in_country = json_decode($site_metrics["alexa_rank_in_country"]);
	$site_metrics["alexa_rank_in_country"] = $alexa_rank_in_country;
	echo json_encode($site_metrics);
}

function get_history_list($domain){
	global $wpdb;
	global $fn;

	$table_name = $GLOBALS['CFG']['Database']['prefix'].'wpspy_activity_log';

	$sql = "SELECT id, DATE_FORMAT(activity_date,'%W, %M %e, %Y @ %h:%i %p') as formatted_activity_date,
	 		activity_date FROM ".$table_name." WHERE url = '".$domain."' ORDER BY id DESC";
	$res = $fn->fetch( $sql, ARRAY_A );
	
	$records = array();

	if(count($res) > 0){
		
		foreach ($res as $key) {
			$action = '<a href="javascript:void(0);" class="history-actions" data-action="site_info" data-id="'.$key["id"].'">Site Info</a>
				<a href="javascript:void(0);" class="history-actions" data-action="page_info" data-id="'.$key["id"].'">Page Info</a>
				<a href="javascript:void(0);" class="history-actions" data-action="seo_stats" data-id="'.$key["id"].'">SEO Stats</a>
				<a href="javascript:void(0);" class="history-actions" data-action="social_stats" data-id="'.$key["id"].'">Social Stats</a>
				<a href="javascript:void(0);" class="history-actions" data-action="traffic" data-id="'.$key["id"].'">Traffic</a>
				<a href="javascript:void(0);" class="history-actions" data-action="link" data-id="'.$key["id"].'">Link</a>';
			$record = array( $key["formatted_activity_date"], $action );
			array_push($records, $record);
		}
		echo json_encode( array( "data" =>$records ) );
	}else{
		echo json_encode( array( "data" => array() ) );
	}
}

function get_history($id, $option){
	
	if($option == "site_info"){
		$columns = "robot, sitemap_index, ip, city, country, country_code, wordpress_data, dns";

	}else if($option == "page_info"){
		$columns = "canonical_url, title, meta_keywords, meta_description, meta_robots, 
					h1, h2, bold_strong, italic_em, body";

	}else if($option == "seo_stats"){
		$columns = "page_indexed_ask, page_indexed_baidu, page_indexed_bing, page_indexed_goo, 
			page_indexed_google, page_indexed_sogou, page_indexed_yahoo, page_indexed_yandex, page_indexed__360,
			backlinks_alexa, backlinks_google, backlinks_open_site_explorer, backlinks_sogou, backlinks_ahrefs,
			alexa_rank, google_page_rank, quantcast_traffic_rank";

	}else if($option == "traffic"){
		$columns = "alexa_rank, alexa_rank_in_country, quantcast_traffic_rank, bounce_rate, 
			daily_pageviews_per_visitor, dailytime_onsite ";

	}else if( $option == "link" ){
		$columns = "external_links, internal_links ";

	}else if( $option == "social_stats" ){
		$columns = "facebook_count, twitter_count, google_count, linkedin_count, 
			pinterest_count, stumbleupon_count, score_strength, score_sentiment, score_passion, score_reach ";
	}

	$res = query_history_sql($id, $columns);
	echo json_encode($res);
	
}

function query_history_sql($id, $columns){
	global $wpdb;
	global $fn;
	$table_name = $GLOBALS['CFG']['Database']['prefix'].'wpspy_activity_log';

	$sql = "SELECT ".$columns." FROM ".$table_name." WHERE id = '".$id."'";
	$res = $fn->fetch( $sql, ARRAY_A );

	if( $res ){
		return $res;
	}
	return $wpdb->last_query;
}

// save recommended tools limit settings
function saveRTLSettings($val){
	global $wpdb;
	global $fn;
	$table_name = $GLOBALS['CFG']['Database']['prefix'].'wpspy_activity_settings';

	$sql = "SELECT * FROM ".$table_name." ORDER BY id DESC LIMIT 1";
	$res = $fn->get_row( $sql );
	pre($res);
	if( !empty($res) ){
		$sql = $fn->update( $table_name, array('recommended_tools_limit' => $val), array("id" => $res->id) );
	}else{
		$fn->insert($table_name, array('recommended_tools_limit' => $val));
	}
	return $fn->last_query;
}

function getRTLimit(){
	global $wpdb;
	global $fn;
	$table_name = $GLOBALS['CFG']['Database']['prefix'].'wpspy_activity_settings';

	$sql = "SELECT recommended_tools_limit FROM $table_name ORDER BY id DESC LIMIT 1";
	$res = $fn->get_row($sql);
	if( !empty($res) && isset($res->recommended_tools_limit) ){
		return $res->recommended_tools_limit;
	}
	return 10;
}


function ajaxCheckDataStatus($option, $url){
	global $wpdb;
	global $fn;
	$table_name = $GLOBALS['CFG']['Database']['prefix'].'wpspy_activity_log';

	$date_now = date("Y-m-d H:i:s");
 	$sql = "SELECT id FROM ".$table_name." WHERE (url='$url') AND 
 			(DATE_FORMAT(activity_date,'%Y-%m-%d') = DATE_FORMAT('$date_now', '%Y-%m-%d')) 
 			ORDER BY id DESC LIMIT 1";
 	$res = $fn->fetch($sql, true);
	pre($res);
 	if( $res > 0 ){
 		$id = $res;

 		if($option == "site_info"){
			$columns = "robot, sitemap_index, ip, city, country, country_code, wordpress_data, dns";

		}else if($option == "page_info"){
			$columns = "canonical_url, title, meta_keywords, meta_description, meta_robots, 
						h1, h2, bold_strong, italic_em, body, external_links, internal_links";

		}else if($option == "seo_stats"){
			$columns = "page_indexed_ask, page_indexed_baidu, page_indexed_bing, page_indexed_goo, 
				page_indexed_google, page_indexed_sogou, page_indexed_yahoo, page_indexed_yandex, page_indexed__360,
				backlinks_alexa, backlinks_google, backlinks_open_site_explorer, backlinks_sogou, backlinks_ahrefs,
				alexa_rank, google_page_rank, quantcast_traffic_rank";

		}else if($option == "traffic"){
			$columns = "alexa_rank, alexa_rank_in_country, quantcast_traffic_rank, bounce_rate, 
				daily_pageviews_per_visitor, dailytime_onsite ";

		}else if( $option == "link" ){
			$columns = "external_links, internal_links ";

		}else if( $option == "social_stats" ){
			$columns = "facebook_count, twitter_count, google_count, linkedin_count, 
				pinterest_count, stumbleupon_count, score_strength, score_sentiment, score_passion, score_reach ";
		}

		// get the details
		$sql = "SELECT ".$columns." FROM ".$table_name." WHERE id = '".$id."'";

		$results = $fn->fetch($sql, false);
		echo json_encode($results);
 	}else{
 		echo "false";
 	}

}
