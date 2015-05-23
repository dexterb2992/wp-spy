<!-- Footer
================================================== -->

	</div> <!-- /.span9 -->
	</div> <!-- /.row -->
	<footer>
		<hr>
		<p>
			&copy; All rights reserved 2013 | <a href="home.php"><?php if (class_exists('Generic')) {
    $generic = new Generic(); echo $generic->getOption('site-name');} ?></a>
			
		</p>
	</footer>

</div> <!-- /.container -->

	<!-- Le javascript -->
	
	<script src="assets/js/bootstrap-transition.js"></script>
	<script src="assets/js/bootstrap-collapse.js"></script>
	<script src="assets/js/bootstrap-modal.js"></script>
	<script src="assets/js/bootstrap-dropdown.js"></script>
	<script src="assets/js/bootstrap-button.js"></script>
	<script src="assets/js/bootstrap-tab.js"></script>
	<script src="assets/js/bootstrap-alert.js"></script>
	<script src="assets/js/bootstrap-tooltip.js"></script>
	<script src="assets/js/jquery.ba-hashchange.min.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/jquery.placeholder.min.js"></script>
	<script src="assets/js/jquery.jigowatt.js"></script>

  </body>
</html>
<?php ob_flush(); ?>