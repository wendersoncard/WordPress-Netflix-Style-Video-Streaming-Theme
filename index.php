<?php get_header(); ?>

	<?php if ( get_theme_mod( 'streamium_enable_loader' ) ) : ?>
		<div class="streamium-loading">&#8230;</div>
	<?php endif; ?>

	<header class="cd-main-header">

		<?php if ( get_theme_mod( 'streamium_logo' ) ) : ?>

		    <a class="cd-logo" href="<?php echo esc_url( home_url('/') ); ?>"><img src='<?php echo esc_url( get_theme_mod( 'streamium_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a>

		<?php else : ?>

		    <a class="cd-logo" href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo( 'name' ); ?></a>

		<?php endif; ?>

		<ul class="cd-header-buttons">
			<li><a class="cd-search-trigger" href="#cd-search"><?php _e( 'Search', 'streamium' ); ?><span></span></a></li>
			<li><a class="cd-nav-trigger" href="#cd-primary-nav"><?php _e( 'Menu', 'streamium' ); ?><span></span></a></li>
		</ul> <!-- cd-header-buttons -->
		<?php get_search_form(); ?>
		
	</header>

	<main class="cd-main-content">

		<?php get_template_part('templates/content', 'slider'); ?>

		<div id="recently-watched"></div>

		<div id="custom-watched"></div>

		<div id="home-watched"></div>

		<div class="main-spacer"></div>

		<div class="cd-overlay"></div>
		
	</main><!--/.main content-->

<?php get_footer(); ?>