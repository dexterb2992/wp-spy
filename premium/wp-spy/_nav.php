<script type="text/javascript">
    var wpspy_ajaxurl = "<?php echo plugins_url('/wp-spy/js/ajax.php');?>";
    var wpspy_imageurl = "<?php echo plugins_url('/wp-spy/js/images/');?>";
</script>
<?php 
	include "_styles.php";
	include "_scripts.php";
	error_reporting(0);
	ini_set('display_errors', 0);
	ini_set('max_execution_time', 300); //300 seconds = 5 minutes

?>
<?php
$u1 = plugins_url();
global $wpdb;
$wpp_msg = '';
$lickey = '';
	$xx =BASE_URL.'js/license.dx';
	$lickey = file_get_contents($xx);
	//$ip = magic_getRealIpAddr_lead();
	$domain = $_SERVER['HTTP_HOST'];
	$location = "admin.php?page=wpspy-dashboard&atoken=$lickey";
?>
<script>
	$(window).load(function(){
		console.log("key: <?php echo $lickey; ?>");
		console.log("xx: <?php echo $xx; ?>");
		var key = "<?php echo $lickey; ?>";
		var page = "<?php echo $page; ?>";
		if( trim(key) == "" ){
			if( page != "wpspy-keycheck" ){
				window.location = "?page=wpspy-keycheck";
			}
		}

		$.ajax({
			url : "http://topdogimsoftware.com/spylicense/index.php?licensekey=<?php echo $lickey; ?>&domainname=<?php echo $domain; ?>&wptype='premium'&format=json&jsoncallback=?",
			type : 'get',
			dataType : 'json'
		}).done(function(data){
			console.log("done");
			var wpspyajax = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
			if(data.response == 'Valid'){
				// it's alright
				console.log("key valid");
			}else if(data.response == 'Invalid'){	
					
				if( page != "wpspy-keycheck" ){
					window.location = "?page=wpspy-keycheck";
				}
			}
		}).fail(function(data){
			console.log("fail");
			if( page != "wpspy-keycheck" ){
				window.location = "?page=wpspy-keycheck";
			}
		});

		function trim(text) {
			return text.replace(/^\s+|\s+$/g, "");
		}

	});
</script>
<div class="wpspy-head">
	<div class="logo">
		<img src="<?php echo plugins_url('/wp-spy/images/spy.png')?>" draggable="false">
	</div>
<?PHP if($_REQUEST['page']!="wpspy-keycheck") { ?>
	<div class="nav">
		<div class="nav-menu <?php echo ( $page == 'wpspy-site-info') ? 'selected' : '';?>">
			<a href="?page=wpspy-site-info<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="?page=wpspy-site-info">Site Info</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-page-info') ? 'selected' : '';?>">
			<a href="?page=wpspy-page-info<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="?page=wpspy-page-info">Page Info</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-seo-stats') ? 'selected' : '';?>">
			<a href="?page=wpspy-seo-stats<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="?page=wpspy-seo-stats" id="nav_seo_stats">SEO Stats</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-social-stats') ? 'selected' : '';?>">
			<a href="?page=wpspy-social-stats<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="?page=wpspy-social-stats">Social Stats</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-traffic') ? 'selected' : '';?>">
			<a href="?page=wpspy-traffic<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="?page=wpspy-traffic">Traffic</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-links') ? 'selected' : '';?>">
			<a href="?page=wpspy-links<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="?page=wpspy-links" id="nav_links">Links</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-graphs') ? 'selected' : '';?>">
			<a href="?page=wpspy-graphs<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="?page=wpspy-graphs">Graphs</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-tutorials') ? 'selected' : '';?>">
			<a href="?page=wpspy-tutorials<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="?page=wpspy-tutorials">Tutorials</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-support') ? 'selected' : '';?>">
			<a href="?page=wpspy-support<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="?page=wpspy-support">Support</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'previous-searches') ? 'selected' : '';?> pull-right">
			<a href="?page=wpspy-previous-searches<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="?page=wpspy-previous-searches">History</a>
		</div>
	</div>
<?PHP  } ?>
</div>
<div class="loading"><div class="center">Grabbing data all over the web...</div></div>