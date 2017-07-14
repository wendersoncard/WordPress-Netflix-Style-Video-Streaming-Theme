
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

				<?php if ( has_nav_menu( 'streamium-header-menu', 'streamium' ) ) :
					
					wp_nav_menu( array( 'container' => false, 'theme_location' => 'streamium-header-menu' ) );

				else :  
					
					echo '<ul id="cd-primary-nav" class="cd-primary-nav is-fixed"><li><a href="#">!To display a menu here go to Apperance and menus create a menu and select (Display location Header Menu).</a></li></ul>';

				endif;

				$postTypes = array(
					array('tax' => 'movies','type' => 'movie','menu' => 'Movies'),
	                array('tax' => 'programs','type' => 'tv','menu' => 'TV Programs'),
	                array('tax' => 'sports','type' => 'sport','menu' => 'Sport'),
	                array('tax' => 'kids','type' => 'kid','menu' => 'Kids'),
	                array('tax' => 'streams','type' => 'stream','menu' => 'Streams')
	            );

				foreach ($postTypes as $key => $value) : ?> 
					
					<?php 
						
						$tax = $value['tax'];
						$type = $value['type'];
						$menu = $value['menu'];

						if ( get_theme_mod( 'streamium_section_checkbox_enable_' . $tax )) :

						$taxTitle = get_theme_mod( 'streamium_section_input_menu_text_' . $type, $menu);
						$typeUrls = get_theme_mod( 'streamium_section_input_posttype_' . $type, $type);
						$taxUrls  = get_theme_mod( 'streamium_section_input_taxonomy_' . $tax, $tax );

					?>

						<li class="has-children">
						<a href="<?php echo esc_url( home_url('/') ); ?>"><?php _e( $taxTitle, 'streamium' ); ?></a>
						<ul class="cd-secondary-nav is-hidden">
							<li class="go-back">
								<a href="#0"><?php _e( 'Menu', 'streamium' ); ?></a>
							</li>
							<li class="see-all">
								<a href="<?php echo esc_url( home_url('/' . $typeUrls) ); ?>"><?php _e( "All " . strtoupper($typeUrls), 'streamium' ); ?></a>
							</li>

							<?php 

							$categories = get_terms( array( 'taxonomy' => $tax, 'parent'   => 0 ));

							if(wp_is_mobile()) : 

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
											<li class="see-all"><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>"><?php echo 'All ' . strtoupper($category->name); ?></a></li>
											<?php $ChildCats = get_categories('child_of=' . $category->cat_ID); 
									            foreach ($ChildCats as $ChildCat) { ?>
												<li><a href="<?php echo esc_url(get_category_link( $ChildCat->term_id )); ?>"><?php echo ucwords($ChildCat->cat_name); ?></a></li>
									        <?php } ?>
										</ul>
									</li>

								<?php } else { ?>
									<li><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>"><?php echo ucwords($category->name); ?></a></li>
								<?php } } ?>

							<?php else: 

								foreach ( partition($categories, 4)  as $key => $parentCategory ) { ?>

							    	<li class="has-children">
								    	
										<ul class="is-hidden">

											<?php 
										    foreach ( $parentCategory  as $key => $category ) {
										        $genre = $category->name; 
										    ?>	

											<li class="go-back"><a href="#0"><?php echo ucwords($category->name); ?></a></li>
											<?php 

												$children = get_term_children( $category->term_id, $tax);
												if($children) : 
											
											?>

												<li class="has-children" id="<?php echo $category->slug; ?>">
													<a href="#0"><?php echo ucwords($category->name); ?></a>

													<ul class="is-hidden">
														<li class="go-back"><a href="#0">Accessories</a></li>
														<li class="see-all"><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>">All <?php echo ucwords($category->name); ?></a></li>
														<?php foreach ($children as $key => $value) { 
												            	$term = get_term($value);
												            ?>
															<li><a href="<?php echo esc_url(get_category_link( $term->term_id )); ?>"><?php echo ucwords($term->name); ?></a></li>
												        <?php } ?>
													</ul>
												</li>

											<?php else : ?>

												<li><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>"><?php echo ucwords($category->name); ?></a></li>

											<?php endif; ?>
			

											<?php }  ?>
										</ul>
									</li>
							    <?php } ?>	
							<?php endif; ?>
						</ul>				
					</li>
					
				<?php 

					endif;
					endforeach;

				?>
				
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