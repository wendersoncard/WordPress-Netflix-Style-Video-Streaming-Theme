<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

	<!-- Meta Data -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- Trackback -->
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<!-- Wordpress Scripts -->
	<?php wp_head(); ?>

	<?php

		// CHECK FOR GOOGLE ADSENSE::
		if(get_theme_mod( 'streamium_advertisement_google_adsense', false )){ ?>

			<!-- GOOGE ADSENSE -->
			<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<script>
			     (adsbygoogle = window.adsbygoogle || []).push({
			          google_ad_client: "<?php echo get_theme_mod( 'streamium_advertisement_google_adsense' ); ?>",
			          enable_page_level_ads: true
			     });
			</script>

	<?php

		}

	?>

</head> 
<body <?php body_class(); ?>>

	<header class="cd-main-header fixed">

		<?php if ( get_theme_mod( 'streamium_logo' ) && filter_var(get_theme_mod( 'streamium_logo' ), FILTER_VALIDATE_URL) ) : ?>

		    <a class="cd-logo" href="<?php echo esc_url( home_url('/') ); ?>"><img src='<?php echo esc_url( get_theme_mod_ssl( 'streamium_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a>

		<?php else : ?>

		    <a class="cd-logo" href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo( 'name' ); ?></a>

		<?php endif; ?>

		<nav class="cd-nav">
			<ul id="cd-primary-nav" class="cd-primary-nav is-fixed">

				<?php 

				$postTypes = streamium_global_post_types();

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

						<li class="menu-item-has-children">
						<a href="<?php echo esc_url( home_url('/') ); ?>"><?php _e( $taxTitle, 'streamium' ); ?></a>
						<ul class="sub-menu is-hidden">
							<li class="go-back">
								<a href="#"><?php _e( 'Menu', 'streamium' ); ?></a>
							</li>
							<li class="see-all">
								<a href="<?php echo esc_url( home_url('/' . $typeUrls) ); ?>"><?php _e( 'View All', 'streamium' ); ?></a>
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
							        <li class="menu-item-has-children"><a href="<?php echo esc_url(get_term_link( $category->term_id )); ?>"><?php echo $category->name; ?></a>
										<ul class="is-hidden">
											<li class="go-back"><a href="#"><?php echo $category->name; ?></a></li>
											<li class="see-all"><a href="<?php echo esc_url(get_term_link( $category->term_id )); ?>"><?php echo __( 'All', 'streamium' ) . ' ' . strtoupper($category->name); ?></a></li>
											<?php $ChildCats = get_term_children( $category->term_id, $tax);
									            foreach ($ChildCats as $ChildCat) { 
									            	$term = get_term($ChildCat);
									        ?>
												<li><a href="<?php echo esc_url(get_term_link( $term->term_id )); ?>"><?php echo ucwords($term->name); ?></a></li>
									        <?php } ?>
										</ul>
									</li>

								<?php } else { ?>
									<li><a href="<?php echo esc_url(get_term_link( $category->term_id )); ?>"><?php echo ucwords($category->name); ?></a></li>
								<?php } } ?>

							<?php else: 

								foreach ( partition($categories, 4)  as $key => $parentCategory ) { ?>

							    	<li class="menu-item-has-children">
								    	
										<ul class="is-hidden">

											<?php 
										    foreach ( $parentCategory  as $key => $category ) {
										        $genre = $category->name; 
										    ?>	

											<li class="go-back"><a href="#"><?php echo ucwords($category->name); ?></a></li>
											<?php 

												$children = get_term_children( $category->term_id, $tax);
												if($children) : 
											
											?>

												<li class="menu-item-has-children">
													<a href="#"><?php echo ucwords($category->name); ?></a>

													<ul class="is-hidden">
														<li class="go-back"><a href="#"></a></li>
														<li class="see-all"><a href="<?php echo esc_url(get_term_link( $category->term_id )); ?>"><?php _e( 'All', 'streamium' ); ?> <?php echo ucwords($category->name); ?></a></li>
														<?php foreach ($children as $key => $value) { 
												            	$term = get_term($value);
												            ?>
															<li><a href="<?php echo esc_url(get_term_link( $term->term_id )); ?>"><?php echo ucwords($term->name); ?></a></li>
												        <?php } ?>
													</ul>
												</li>

											<?php else : ?>

												<li><a href="<?php echo esc_url(get_term_link( $category->term_id )); ?>"><?php echo ucwords($category->name); ?></a></li>

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

					if ( has_nav_menu( 'streamium-header-menu', 'streamium' ) ) :

						echo str_replace('sub-menu', 'sub-menu is-hidden', wp_nav_menu( array(
						    'echo' => false,
						    'container' => false, 
						    'theme_location' => 'streamium-header-menu',
						  ) )
						);
						
					else :  
						
						printf('<ul id="cd-primary-nav" class="cd-primary-nav is-fixed"><li><a href="/wp-admin/nav-menus.php" target="_blank">%1$s</a></li></ul>', __( 'Add Menu (Location Header Menu)', 'streamium' ));

					endif;

				?>
				
			</ul> <!-- primary-nav -->
		</nav> <!-- cd-nav -->

		<ul class="cd-header-buttons">
			<li><a class="cd-search-trigger" href="#cd-search"><?php _e( 'Search', 'streamium' ); ?><span></span></a></li>
			<li><a class="cd-nav-trigger" href="#cd-primary-nav"><?php _e( 'Menu', 'streamium' ); ?><span></span></a></li>
		</ul> <!-- cd-header-buttons -->
		<?php get_search_form(); ?>
		
	</header>