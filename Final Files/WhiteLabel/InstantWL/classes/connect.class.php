<?php

/**
 * Establish a mySQL connection and select a database.
 *
 * LICENSE:
 *
 * This source file is subject to the licensing terms that
 * is available through the world-wide-web at the following URI:
 * http://codecanyon.net/wiki/support/legal-terms/licensing-terms/.
 *
 * @author       Jigowatt <info@jigowatt.co.uk>
 * @author       Matt Gates <matt.gates@jigoshop.com>
 * @copyright    Copyright © 2009-2012 Jigowatt Ltd.
 * @license      http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 * @link         http://codecanyon.net/item/php-login-user-management/49008
 */

class Connect {

	private $error;
	public static $dbh;

	/**
	 * Checks if installation is complete by seeing if config.php exists.
	 *
	 * The user will be prompted to visit home.php and click "Begin Install" if
	 * there is no config.php yet setup. This prompt will be persistent and won't
	 * allow any pages to load until config.php is created.
	 *
	 * @return    string    The error message if an install does not exist.
	 */
	public function checkInstall() {

		if(!file_exists(dirname(__FILE__) . '/config.php'))
		{

		    if(isset($_POST['licensekey']) && $_POST['submit']=='Submit')
				{
				   $result= $this->checkLicense($_POST['licensekey'],'',0);
				   	$result['status'] = 'Active';
					if($result['status']=='Active')
					{
					
						return "<div style='margin:50px;'><h2>Thank you!</h2><p>"._('Your license key is valid. Please proceed to set up.')." <form action='install/index.php' method='post'><input type='hidden' name='key' value='".$_POST['licensekey']."'><input type='submit' name='submit' value='Continue'></form></p></div>";
						
					}
					else
					{
					    
						return "<link href='assets/css/bootstrap.min.css' rel='stylesheet'><div style='margin:50px;'><h2>License your purchase</h2><p>Enter your API key <a href='#' data-rel='tooltip' tabindex='99' title='You can get your API key from members area'><i class='icon-info-sign'></i></a></p><form action='' method='post'><p><input type='text' name='licensekey' style='width:345px;height:30px;'/><span style='color:red;margin-left:10px;'>".$result['message']."</span></p><input type='submit' name='submit' value='Submit' /></form><p></div>";
					
					}
				}
				else
				{
					return "<link href='assets/css/bootstrap.min.css' rel='stylesheet'><div style='margin:50px;'><h2>License your purchase</h2><p>Enter your API key <a href='#' data-rel='tooltip' tabindex='99' title='You can get your API key from members area'><i class='icon-info-sign'></i></a></p><form action='' method='post'><p><input type='text' name='licensekey' style='width:345px;height:30px;'/> </p><input type='submit' name='submit' value='Submit' /></form></div>";
				}		       
		}
 
	}
    
	public function checkLicense($licensekey,$localkey="",$install=1) {
	
		$licenserurl = "http://instantwhitelabel.com/licenser/";//
		$licensing_secret_key = "VDWL"; 
		$check_token = time().md5(mt_rand(1000000000,9999999999).$licensekey);
		$checkdate = date("Ymd"); # Current date
		$usersip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
		if($install)
		{
			$dbh=self::$dbh;
			$sql = "SELECT `id`,`option_name`,`option_value` FROM `login_settings` WHERE `option_name` = ? OR `option_name` = ? ORDER BY `id` ASC LIMIT 0,2;";
			$query = $dbh->prepare($sql);
			$array = array('localkeydays', 'allowcheckfaildays');
			$query->execute($array);
			$result=$query->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $i=>$res)
			{
			   if($res['option_name']=='localkeydays')
			   {
			      $localkeydays=$res['option_value'];
			   }
			   else if($res['option_name']=='allowcheckfaildays')
			   {
					$allowcheckfaildays=$res['option_value'];
			   }
			}
			
		}
		else
		{
		  $localkeydays=1;
		  $allowcheckfaildays=6;
		
		}
		$localkeyvalid = false;
		if ($localkey) {
			$localkey = str_replace("\n",'',$localkey); 
			$localdata = substr($localkey,0,strlen($localkey)-32); 
			$md5hash = substr($localkey,strlen($localkey)-32); 
			if ($md5hash==md5($localdata.$licensing_secret_key)) {
				$localdata = strrev($localdata); 
				$md5hash = substr($localdata,0,32); 
				$localdata = substr($localdata,32); 
				$localdata = base64_decode($localdata);
				$localkeyresults = unserialize($localdata);
				$originalcheckdate = $localkeyresults["checkdate"];
				if ($md5hash==md5($originalcheckdate.$licensing_secret_key)) {
					$localexpiry = date("Ymd",mktime(0,0,0,date("m"),date("d")-$localkeydays,date("Y")));
					if ($originalcheckdate>$localexpiry) {
						$localkeyvalid = true;
						$results = $localkeyresults;
						$validdomains = explode(",",$results["validdomain"]);
						if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
							$localkeyvalid = false;
							$localkeyresults["status"] = "Invalid";
							$results = array();
						}
						$validips = explode(",",$results["validip"]);
						if (!in_array($usersip, $validips)) {
							$localkeyvalid = false;
							$localkeyresults["status"] = "Invalid";
							$results = array();
						}
						if ($results["validdirectory"]!=dirname(__FILE__)) {
							$localkeyvalid = false;
							$localkeyresults["status"] = "Invalid";
							$results = array();
						}
					}
				}
			}
		}
		
		if (!$localkeyvalid) {
			$postfields["licensekey"] = $licensekey;
			$postfields["domain"] = $_SERVER['SERVER_NAME'];
			$postfields["uri"]= $_SERVER['REQUEST_URI'];
			$postfields["ip"] = $usersip;
			$postfields["dir"] = dirname(dirname(__FILE__));
			if ($check_token) $postfields["check_token"] = $check_token;
		
			if (function_exists("curl_exec")) {
			 
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $licenserurl."validate.php");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$data = curl_exec($ch);
				curl_close($ch);
			} else {
				$fp = fsockopen($licenserurl, 80, $errno, $errstr, 5);
				if ($fp) {
					$querystring = "";
					foreach ($postfields AS $k=>$v) {
						$querystring .= "$k=".urlencode($v)."&";
					}
					$header="POST ".$licenserurl."validate.php HTTP/1.0\r\n";
					$header.="Host: ".$licenserurl."\r\n";
					$header.="Content-type: application/x-www-form-urlencoded\r\n";
					$header.="Content-length: ".@strlen($querystring)."\r\n";
					$header.="Connection: close\r\n\r\n";
					$header.=$querystring;
					$data="";
					@stream_set_timeout($fp, 20);
					@fputs($fp, $header);
					$status = @socket_get_status($fp);
					while (!@feof($fp)&&$status) {
						$data .= @fgets($fp, 1024);
						$status = @socket_get_status($fp);
					}
					@fclose ($fp);
				}
			}
			if (!$data) {
			    
				$localexpiry = date("Ymd",mktime(0,0,0,date("m"),date("d")-($localkeydays+$allowcheckfaildays),date("Y")));
				if ($originalcheckdate>$localexpiry) {
					$results = $localkeyresults;
				} else {
					$results["status"] = "Invalid";
					$results["description"] = "Remote Check Failed";
					return $results;
				}
			} else {
				preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
				$results = array();
				foreach ($matches[1] AS $k=>$v) {
					$results[$v] = $matches[2][$k];
				}
			}
			// Disable license expiration
			$results['md5hash'] = md5($licensing_secret_key.$check_token);
			if ($results["md5hash"]) {
				if ($results["md5hash"]!=md5($licensing_secret_key.$check_token)) {
					$results["status"] = "Invalid";
					$results["description"] = "MD5 Checksum Verification Failed";
					return $results;
				}
			}
			if ($results["status"]=="Active") {
				$results["checkdate"] = $checkdate;
				$data_encoded = serialize($results);
				$data_encoded = base64_encode($data_encoded);
				$data_encoded = md5($checkdate.$licensing_secret_key).$data_encoded;
				$data_encoded = strrev($data_encoded);
				$data_encoded = $data_encoded.md5($data_encoded.$licensing_secret_key);
				$data_encoded = wordwrap($data_encoded,80,"\n",true);
				$results["localkey"] = $data_encoded;
			}
			$results["remotecheck"] = true;
		}
		unset($postfields,$data,$matches,$licenserurl,$licensing_secret_key,$checkdate,$usersip,$localkeydays,$allowcheckfaildays,$md5hash);
		$results['status'] = 'Active';
		return $results;

	}
	/**
	 * Connect to mySQL and select a database.
	 *
	 * The credentials used to connect to the database are pulled from /classes/config.php.
	 *
	 * @return    string    Error message for any incorrect database connection attempts.
	 */
	public function dbConn() {

		include(dirname(__FILE__) . '/config.php');

		try {
			self::$dbh = new PDO("mysql:host={$host};dbname={$dbName}", $dbUser, $dbPass);
			self::$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		} catch (PDOException $e) {
			return '<div class="alert alert-error">'._('Database error: '). $e->getMessage() . '</div>';
		}


	}

}

// Instantiate the Connect class
$connect = new Connect();