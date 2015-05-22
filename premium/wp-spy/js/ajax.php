<?php 

include "../classes/config.php";
include_once('../../../../wp-config.php');
include "../classes/data.php";
include "ajax-functions.php";

$q = $_POST['q'];

global $wpspy_activity;

$wpdb->show_errors = true; // true : show errors in development

global $wpdb;
$table_name = $wpdb->prefix.'wpspy_activity_log';

$domain = $_POST['url'];

$format = 'json';

if( $q == "save_activity" ){

	$wpspy_activity = array();
	foreach ($_POST as $key => $value) {
		if($key != "q"){
			$wpspy_activity[(string) $key] = (string) $value;
		}
	}
	store_activity($domain, $wpspy_activity);

}else if( $q == "get_alexa_rank" ){

	get_alexa_rank($domain);

}else if( $q == "get_chart_data" ){

	get_chart_data($domain, $_POST['col']);

}else if( $q == "get_social_mention_links" ){

	// get_social_mention_links($domain);
	echo getSocialMentionLinks($domain);

}else if( $q == "get_sites" ){

	get_sites_json();	

}else if( $q == "get_whois" ){

	// get_whois($domain);
	echo getWhOIS($domain, $format);

}else if ( $q == "get_onsite" ){

	// get_onsite($domain);
	echo getOnSite($domain, $format);

}else if( $q == "get_wordpress_data" ){

	// get_wordpress_data($domain);
	echo getWordpressData($domain, $format);

}else if( $q == "get_page_info" ){

	// get_page_info($domain);
	echo getPageInfo($domain, $format);

}else if( $q == "get_seo_stats" ){

	// get_seo_stats($domain);
	echo getSeoStats($domain, $format);

}else if( $q == "get_ie_links" ){

	echo get_ie_links($domain);

}else if( $q == "get_social_mention" ){

	// get_social_mention($domain);
	echo getSocialMention($domain);

}else if( $q == "get_social_stats" ){

	echo getSociaLStats($domain);

}else if( $q == "get_site_metrics" ){

	echo get_sitemetrics($domain);

}else if ( $q == "get_history_list" ){

	get_history_list($domain);

}else if ( $q == "get_history" ){

	get_history($_POST['id'], $_POST['option']);

}else if( $q == "update_rtl_settings" ){
	
	saveRTLSettings($_POST['val']);

}else if ( $q == "get_rtlimit" ){
	
	echo getRTLimit();

}else if( $q == "check_status" ){
	
	ajaxCheckDataStatus($_POST['option'], $domain);

}else if( $q == "get_ranks" ){
	echo json_encode(
		array( 
			"alexa_rank" => get_alexa_traffic_rank($domain),
			"quantcast_traffic" => get_quantcast_rank($domain),
			"google_page_rank" => get_google_page_rank($domain)
		)
	);
}else if( $q == "get_backlinks" ){
	$seo = new SEOStats($domain);
	$ahrefs = new Ahrefs();
	$ahrefs->setHtml($domain);

	echo json_encode(
		array(
			"alexa" => "http://www.alexa.com/siteinfo/".$domain,
			"open_site_explorer" => "https://moz.com/researchtools/ose/links?site=".urlencode($domain),
			"google" => $seo->get_GBL(),
			"ahrefs" => ($ahrefs->getBackLinks() > 0) ? $ahrefs->getBackLinks() : 'https://ahrefs.com/site-explorer/export/csv/subdomains/?target='.substr($domain, 7),
			"sogou" => ($seo->get_SogouBL() != "N/A") ? $seo->get_SogouBL() : "http://www.sogou.com/web?query=link: ".$_url["host"]
		)
	);
}else if( $q == "get_pages_indexed" ){
	$seo = new SEOStats($domain);
	echo json_encode(
		array(
			"ask" => "http://www.ask.com/web?q=".urlencode($domain),
			"baidu" => $seo->get_BaiduIP(),
			"bing" => $seo->get_BingIP(),
			"goo" => $seo->get_GooIP(),
			"google" => $seo->get_GIP(),
			"sogou" => $seo->get_SogouIP(),
			"yahoo" => $seo->get_YahooIP(),
			"yandex" => $seo->get_YandexIp(),
			"_360" => $seo->get_360Ip()
		)
	);
}else if( $q == "get_alexa_rank_in_country" ){
	$alexa = new Alexa();
	$alexa->setXml($domain);
	echo json_encode(
		array(
			"alexa_rank_in_country" => $alexa->getRankInCountry(),
			"bounce_rate" => $alexa->getBounceRate(),
			"dailytime_onsite" => $alexa->getDailyPageView(),
			"daily_pageviews_per_visitor" => $alexa->getTimeOnSite()
		)
	);
}