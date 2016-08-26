<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<!-- Meta Data -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
<<<<<<< HEAD
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
=======
>>>>>>> 17c526e13156d8c684e652449df69bee146e2646
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	
	<!-- Trackback -->
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<!-- Wordpress Scripts -->
	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
	<header class="cd-main-header">

		<a class="cd-logo" href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo( 'name' ); ?></a>

		<ul class="cd-header-buttons">
			<li><a class="cd-search-trigger" href="#cd-search"><?php _e( 'Search', 'streamium' ); ?><span></span></a></li>
			<li><a class="cd-nav-trigger" href="#cd-primary-nav"><?php _e( 'Menu', 'streamium' ); ?><span></span></a></li>
		</ul> <!-- cd-header-buttons -->
		<?php get_search_form(); ?>
<<<<<<< HEAD
		
=======
>>>>>>> 17c526e13156d8c684e652449df69bee146e2646
	</header>

	<div class="cd-testimonials-all">
		<div class="cd-testimonials-all-wrapper">
		</div>	<!-- cd-testimonials-all-wrapper -->
		<a href="#0" class="close-btn">Close</a>
	</div> <!-- cd-testimonials-all -->