<?php include_once('classes/check.class.php'); ?>
<?php include_once('header.php'); ?>
<script type="text/javascript">
	$("title").html("WP Spy");
</script>
<div class="features">
	<div class="row">
	<?php if( protectThis("*") ) : ?>
		<div class="flex-video widescreen">
			<center><iframe src="wp-spy" width="1200" height="880" frameborder="0"></iframe></center>
		</div>	
	<?php else : ?>
		<div class="alert alert-warning"><?php _e('<a href="login.php">Sign in</a> first to launch the software'); ?></div>
	<?php endif; ?>

	</div>

	
</div>

<?php include_once('footer.php'); ?>