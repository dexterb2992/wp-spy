<script type="text/javascript">
    var wpspy_ajaxurl = "js/ajax.php";
    var wpspy_imageurl = "js/images/";
</script>
<?php 
	include "_styles.php";
	include "_scripts.php";
	include "classes/config.php";
	include "classes/functions.php";
	include "classes/dbhelper.php";
	include "classes/data.php";

	global $fn;
	global $wpdb;
	$wpdb->prefix = $GLOBALS['CFG']['Database']['prefix'];
	$fn = new functions();

	$fn->connect();
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('max_execution_time', 300); //300 seconds = 5 minutes

?>
<div class="wpspy-head">
	<div class="logo">
		<img src="images/spy.png" draggable="false">
	</div>
	<div class="nav">
		<div class="nav-menu <?php echo ( $page == 'wpspy-site-info') ? 'selected' : '';?>">
			<a href="site-info?<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="site-info?">Site Info</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-page-info') ? 'selected' : '';?>">
			<a href="page-info?<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="page-info?">Page Info</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-seo-stats') ? 'selected' : '';?>">
			<a href="seo-stats?<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="seo-stats?" id="nav_seo_stats">SEO Stats</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-social-stats') ? 'selected' : '';?>">
			<a href="social-stats?<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="social-stats?">Social Stats</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-traffic') ? 'selected' : '';?>">
			<a href="traffic?<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="traffic?">Traffic</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-links') ? 'selected' : '';?>">
			<a href="links?<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="links?" id="nav_links">Links</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-graphs') ? 'selected' : '';?>">
			<a href="graphs?<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="graphs?">Graphs</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-tutorials') ? 'selected' : '';?>">
			<a href="tutorials?<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="tutorials?">Tutorials</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'wpspy-support') ? 'selected' : '';?>">
			<a href="support?<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="support?">Support</a>
		</div>
		<div class="nav-menu <?php echo ( $page == 'previous-searches') ? 'selected' : '';?> pull-right">
			<a href="history?<?php echo isset($_GET['url']) ? '&url='.$_GET['url'] : ''; ?>" data-href="history?">History</a>
		</div>
	</div>
</div>
<div class="loading"><div class="center">Grabbing data all over the web...</div></div>