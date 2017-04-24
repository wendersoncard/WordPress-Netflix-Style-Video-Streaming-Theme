
	</main><!--/.main content-->
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
		<div class="cd-overlay"></div>

		<nav class="cd-nav">
			<ul id="cd-primary-nav" class="cd-primary-nav is-fixed">
				<?php if ( has_nav_menu( 'streamium-header-menu', 'streamium' ) ) { ?>
					<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'streamium-header-menu' ) ); ?>
				<?php } ?>
				<li class="has-children">
					<a href="<?php echo esc_url( home_url('/') ); ?>"><?php _e( (get_theme_mod( 'streamium_genre_text' )) ? get_theme_mod( 'streamium_genre_text' ) : 'Genres', 'streamium' ); ?></a>
					<ul class="cd-secondary-nav is-hidden">
						<li class="go-back"><a href="#0"><?php _e( 'Menu', 'streamium' ); ?></a></li>
						<li class="see-all"><a href="<?php echo esc_url( home_url('/') ); ?>"><?php _e( 'All Videos', 'streamium' ); ?></a></li>
						<?php $args = array(
					      	'orderby' => 'name',
					      	'parent' => 0
					    );
					    $categories = get_categories( $args );
					    foreach ( $categories  as $key => $category ) {
					        $genre = $category->name; 
					        $children = get_terms( $category->taxonomy, array(
					            'parent'    => $category->term_id,
					            'hide_empty' => false
					        ) );
					        if($children) { ?>
					        <li class="has-children"><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>"><?php echo $category->name; ?></a>
								<ul class="is-hidden">
									<li class="go-back"><a href="#0"><?php echo $category->name; ?></a></li>
									<li class="see-all"><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>"><?php echo 'All ' . $category->name; ?></a></li>
									<?php $ChildCats = get_categories('child_of=' . $category->cat_ID); 
							            foreach ($ChildCats as $ChildCat) { ?>
										<li><a href="<?php echo esc_url(get_category_link( $ChildCat->term_id )); ?>"><?php echo $ChildCat->cat_name; ?></a></li>
							        <?php } ?>
								</ul>
							</li>

						<?php } else { ?>
							<li><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>"><?php echo $category->name; ?></a></li>
						<?php } } ?>
					</ul>				
				</li>
			</ul> <!-- primary-nav -->
		</nav> <!-- cd-nav -->

		<div class="streamium-review-panel from-right">
			<header class="streamium-review-panel-header">
				<h1>Reviews</h1>
				<a href="#0" class="streamium-review-panel-close">Close</a>
			</header>

			<div class="streamium-review-panel-container">
				<div class="streamium-review-panel-content">
					
				</div> <!-- streamium-review-panel-content -->
			</div> <!-- streamium-review-panel-container -->
		</div> <!-- streamium-review-panel -->
		<?php if ( !get_theme_mod( 'tutorial_btn' ) ) : ?>
			<div class="streamium-install-instructions"><h2>Please follow this guide for help with installation <a href="https://s3bubble.com/wp_themes/streamium-netflix-style-wordpress-theme/" target="_blank">Video Series</a></h2><p>You can remove this alert in Appearance -> Customizer -> Streamium</p></div>
		<?php endif; ?>
	<?php wp_footer(); ?>
</body>
</html>