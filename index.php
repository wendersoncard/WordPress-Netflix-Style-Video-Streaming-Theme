<?php 

// REDIRECT USERS TO JOIN PAGE::
if ( get_theme_mod( 'streamium_enable_splash_join_redirect', false )) {

	if ( !is_user_logged_in() ) {

		wp_redirect( site_url( '/join' ) );
	  	exit;

	}

}

get_header(); ?>

	<?php 
		if ( get_theme_mod( 'streamium_enable_loader' )) : 
	?>
		<!-- Preloader -->
	    <div class="loader-mask">
	        <div class="loader">
	            "Loading..."
	        </div>
	    </div>
	<?php
		endif; 
	?>

	<main class="cd-main-content">

		<?php get_template_part('templates/content', 'slider'); ?>

		<div id="recently-watched"></div>

		<div id="custom-watched"></div>

		<div id="home-watched"></div>
		
	</main><!--/.main content-->

<?php get_footer(); ?>