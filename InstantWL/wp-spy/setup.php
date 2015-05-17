<?php 
	include "classes/config.php";
	if($GLOBALS['CFG']['Database']['databasename'] != ""){
		$conn = @mysql_connect($GLOBALS['CFG']['Database']['host'], 
			$GLOBALS['CFG']['Database']['username'], $GLOBALS['CFG']['Database']['password']);
		if (mysql_error()) {
			// do nothing, so let's see if the database is valid
		}else{
			$db = mysql_select_db($GLOBALS['CFG']['Database']['databasename']);
			if(!$db){
				// do nothing, which means.. let's set up the database credentials here
			}else{
				header("Location: site-info.php");
			}
			
		}
		
	}
?>
<html>
<head>
	<title>Database Setup Wizard</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<style type="text/css">
		#form_1 {
		  margin-right: auto;
		  margin-left: auto;
		  width: 186px;
		}
		h3 {
		  margin-top: 60px;
		}
		input[type="text"] {
	  	  margin-bottom: 8px;
		  margin-top: 2px;
		}
		input[type="submit"] {
		  float: right;
		  margin-right: 5px;
		  padding-left: 70px;
		  padding-right: 70px;
		}
	</style>
	<div>
		<form method="post" action="" id="form_1">
			<h3>Database Setup Wizard</h3>
			<label for="host">Servername</label><br/>
			<input type="text" name="host" placeholder="Servername" required><br/>

			<label for="database_name">Database name</label><br/>
			<input type="text" name="database_name" placeholder="Database name" required><br/>

			<label for="username">Username</label><br/>
			<input type="text" name="username" placeholder="Username" required><br/>

			<label for="password">Password</label><br/>
			<input type="text" name="password" placeholder="Password"><br/>
			<br/>
			<input type="submit" value="Submit" name="next1">
		</form>
	</div>

	<?php 
		if( isset($_POST['next1']) ){
			try{
				$host = $_POST["host"];
				$database_name = $_POST["database_name"];
				$username = $_POST["username"];
				$password = $_POST["password"];
				$prefix = "wp_";

				print_r($_POST);

				$sql = "CREATE TABLE ".$prefix."wpspy_activity_log (
					id bigint(20) unsigned NOT NULL auto_increment,
					url varchar(255) NOT NULL default '0',

					canonical_url varchar(255) NOT NULL default 'N/A',
					title varchar(255),
					meta_keywords longtext,
					meta_description longtext,
					meta_robots longtext,
					h1 longtext,
					h2 longtext,
					bold_strong longtext,
					italic_em longtext,
					body longtext,

					external_links longtext,
					internal_links longtext,

					alexa_rank varchar(255) NOT NULL default '0',
					google_page_rank varchar(255) NOT NULL default '0',
					quantcast_traffic_rank varchar(255) NOT NULL default '0',
					alexa_rank_in_country longtext,

					robot varchar(5) NOT NULL default '0',
					sitemap_index varchar(5) NOT NULL default '0',

					ip varchar(50) NOT NULL default 'N/A',
					city varchar(255) NOT NULL default 'N/A',
					country varchar(255) NOT NULL default 'N/A',
					country_code varchar(255) NOT NULL default 'N/A',
					dns longtext,

					backlinks_alexa varchar(255) NOT NULL default '0',
					backlinks_google varchar(255) NOT NULL default '0',
					backlinks_open_site_explorer varchar(255) NOT NULL default '0',
					backlinks_sogou varchar(255) NOT NULL default '0',
					backlinks_ahrefs varchar(255) NOT NULL default '0',

					bounce_rate varchar(255) NOT NULL default '0',
					referring_domains varchar(255) NOT NULL default '0',
					referring_ips varchar(255) NOT NULL default '0',
					dailytime_onsite varchar(255) NOT NULL default '-',
					daily_pageviews_per_visitor varchar(255) NOT NULL default '-',

					page_indexed_ask varchar(255) NOT NULL default '0',
					page_indexed_baidu varchar(255) NOT NULL default '0',
					page_indexed_bing varchar(255) NOT NULL default '0',
					page_indexed_goo varchar(255) NOT NULL default '0',
					page_indexed_google varchar(255) NOT NULL default '0',
					page_indexed_sogou varchar(255) NOT NULL default '0',
					page_indexed_yahoo varchar(255) NOT NULL default '0',
					page_indexed_yandex varchar(255) NOT NULL default '0',
					page_indexed__360 varchar(255) NOT NULL default '0',

					facebook_count varchar(11) NOT NULL default '0',
					twitter_count varchar(11) NOT NULL default '0',
					google_count varchar(11) NOT NULL default '0',
					linkedin_count varchar(11) NOT NULL default '0',
					pinterest_count varchar(11) NOT NULL default '0',
					stumbleupon_count varchar(11) NOT NULL default '0',

					score_strength varchar(10) NOT NULL default '0',
					score_sentiment varchar(10) NOT NULL default '-',
					score_passion varchar(10) NOT NULL default '0',
					score_reach varchar(10) NOT NULL default '0 ',
					sentiment varchar(255) NOT NULL default '-',
					top_keywords longtext,

					wordpress_data longtext,

					activity_date datetime NOT NULL default '0000-00-00 00:00:00',
					PRIMARY KEY (id)
				)";

				$arr = array(
					'Database' => array(
						'host'=>$host,
						'databasename' => $database_name,
						'username' => $username,
						'password' => $password,
						'prefix' => $prefix
					)
				);
				$conn = @mysql_connect($host, $username, $password);
				if (!$conn) {
				    die("Connection failed: " . mysql_error()."gwapo ko");
				}else{

					$db = @mysql_select_db($database_name);
					
					if(!$db){
						die('Oh snap! We can\'t connect to Database "'.$database_name.'"\n'.mysql_error());
					}else{
						$myfile = @fopen("db-config.dex", "w");
						@fwrite($myfile, json_encode($arr));
						@fclose($myfile);

						@mysql_query("DROP TABLE IF EXISTS ".$prefix."wpspy_activity_log;");
						$res = @mysql_query($sql);
						if($res){
							header("Location: site-info.php");
						}else{
							echo "Oh, wait! We got a problem. ".mysql_error();
						}
					}
				}
			}catch(Exception $e){

			}
		}
	?>
</body>
</html>

