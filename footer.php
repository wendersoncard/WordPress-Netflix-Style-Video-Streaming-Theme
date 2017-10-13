	<footer class="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<?php if ( get_theme_mod( 'streamium_remove_powered_by_s3bubble' ) ) : ?>

					    <p class="copyright"><?php echo get_theme_mod( 'streamium_remove_powered_by_s3bubble' ); ?></p>

					<?php else : ?>

					    <p class="copyright">Powered by <a href="https://s3bubble.com">S3Bubble.com</a></p>

					<?php endif; ?>
				</div><!--/.col-sm-12-->
			</div><!--/.row-->
		</div><!--/.container-->
	</footer>

	<div class="streamium-review-panel from-right">
		<header class="streamium-review-panel-header">
			<h1><?php _e( 'Reviews', 'streamium' ); ?></h1>
			<a href="#0" class="streamium-review-panel-close"><i class="fa fa-times" aria-hidden="true"></i></a>
		</header>

		<div class="streamium-review-panel-container">
			<div class="streamium-review-panel-content">
				
			</div> <!-- streamium-review-panel-content -->
		</div> <!-- streamium-review-panel-container -->
	</div> <!-- streamium-review-panel -->
	<?php if ( !get_theme_mod( 'tutorial_btn' ) ) : ?>
		<div class="streamium-install-instructions"><h2><?php _e( 'Please follow this guide for help with installation', 'streamium' ); ?> <a href="https://s3bubble.com/wp_themes/streamium-netflix-style-wordpress-theme/" target="_blank"><?php _e( 'Video Series', 'streamium' ); ?></a></h2><p><?php _e( 'You can remove this alert in Appearance -> Customizer -> Streamium<', 'streamium' ); ?>/p></div>
	<?php endif; ?>
	<?php wp_footer(); ?>
	<?php

		// Get the tracking code if it exists
		$trackingCode = get_theme_mod( 'streamium_google_analytics_section_code', '');

		if(empty($trackingCode)){
			
			echo '<!-- Google Tracking Code Below You Can Set This In The Customizer -->';

		}else{ ?>

			<!-- Google Tracking Code Below You Can Set This In The Customizer -->
			<script>
			  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			  ga('create', '<?php echo $trackingCode; ?>', 'auto');
			  ga('send', 'pageview');

			</script>

		<?php } ?>  		
</body>
</html>