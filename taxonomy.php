<?php get_header(); ?>

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

		<section class="categories">

			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 video-header-archive">
						<h3><?php printf( __( 'Viewing: %s', 'streamium' ), single_cat_title( '', false ) ); ?></h3>
						<?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>
							<div class="streamium-drop-dropdown-wrapper open-to-left">
								<a class="streamium-drop-dropdown-trigger" href="#0">FILTER</a>
								<nav class="streamium-drop-dropdown">
									<ul class="streamium-drop-dropdown-content">
										<li><a class="tax-search-filter" data-type="all" href="?sort=all">View All</a></li>
									    <li><a class="tax-search-filter" data-type="reviewed" href="?sort=reviewed">Most Reviews</a></li>
									    <li><a class="tax-search-filter" data-type="newest" href="?sort=newest">Recently Added</a></li>
									    <li><a class="tax-search-filter" data-type="oldest" href="?sort=oldest">Oldest First</a></li>
									</ul> <!-- .cd-dropdown-content -->
								</nav> <!-- .cd-dropdown -->
							</div> <!-- .cd-dropdown-wrapper -->
						<?php endif; ?>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->

			<div id="tax-watched"></div>

		</section><!--/.videos-->

		<div class="main-spacer"></div>

	</main><!--/.main content-->

<?php get_footer(); ?>