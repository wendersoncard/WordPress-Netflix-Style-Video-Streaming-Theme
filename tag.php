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
		if ( get_theme_mod( 'streamium_enable_loader' )) {
			
			get_template_part('templates/content', 'loader');

		} 
	?>
	
	<main class="cd-main-content">

		<section class="categories">

			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 video-header-archive">
						<h3><?php printf( __( 'Viewing: %s', 'streamium' ), single_cat_title( '', false ) ); ?></h3>
						<?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>
							<div class="streamium-drop-dropdown-wrapper open-to-left">
								<a class="streamium-drop-dropdown-trigger" href="#0"><?php _e( 'FILTER', 'streamium' ); ?></a>
								<nav class="streamium-drop-dropdown">
									<ul class="streamium-drop-dropdown-content">
										<?php
											$tags = get_tags( array('orderby' => 'count', 'order' => 'DESC') );
											foreach ( (array) $tags as $tag ) {
												echo '<li><a href="' . get_tag_link ($tag->term_id) . '" rel="tag">' . $tag->name . ' (' . $tag->count . ') </a></li>';
										    }
										?>
									</ul> <!-- .cd-dropdown-content -->
								</nav> <!-- .cd-dropdown -->
							</div> <!-- .cd-dropdown-wrapper -->
						<?php endif; ?>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->

			<div id="tag-watched"></div>

		</section><!--/.videos-->

	</main><!--/.main content-->

<?php get_footer(); ?>