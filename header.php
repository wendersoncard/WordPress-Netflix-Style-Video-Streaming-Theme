<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

	<!-- Meta Data -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- favicons -->
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_bloginfo('template_url'); ?>/production/img/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_bloginfo('template_url'); ?>/production/img/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_bloginfo('template_url'); ?>/production/img/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_bloginfo('template_url'); ?>/production/img/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_bloginfo('template_url'); ?>/production/img/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_bloginfo('template_url'); ?>/production/img/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_bloginfo('template_url'); ?>/production/img/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_bloginfo('template_url'); ?>/production/img/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_bloginfo('template_url'); ?>/production/img/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_bloginfo('template_url'); ?>/production/img/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_bloginfo('template_url'); ?>/production/img/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_bloginfo('template_url'); ?>/production/img/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_bloginfo('template_url'); ?>/production/img/favicon-16x16.png">

	<!-- Trackback -->
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<!-- Wordpress Scripts -->
	<?php wp_head(); ?>

</head> 
<body <?php body_class(); ?>>

	<?php 
		if ( get_theme_mod( 'streamium_enable_loader' ) && is_home() ) : 
	?>
		<div class="streamium-loading">&#8230;</div>
	<?php
		endif; 
	?>

	<header class="cd-main-header fixed">

		<?php if ( get_theme_mod( 'streamium_logo' ) ) : ?>

		    <a class="cd-logo" href="<?php echo esc_url( home_url('/') ); ?>"><img src='<?php echo esc_url( get_theme_mod_ssl( 'streamium_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a>

		<?php else : ?>

		    <a class="cd-logo" href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo( 'name' ); ?></a>

		<?php endif; ?>

		<nav class="cd-nav">
			<ul id="cd-primary-nav" class="cd-primary-nav is-fixed">

				<?php 

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

						<li class="menu-item-has-children">
						<a href="<?php echo esc_url( home_url('/') ); ?>"><?php _e( $taxTitle, 'streamium' ); ?></a>
						<ul class="sub-menu is-hidden">
							<li class="go-back">
								<a href="#0"><?php _e( 'Menu', 'streamium' ); ?></a>
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
							        <li class="menu-item-has-children"><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>"><?php echo $category->name; ?></a>
										<ul class="is-hidden">
											<li class="go-back"><a href="#0"><?php echo $category->name; ?></a></li>
											<li class="see-all"><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>"><?php echo __( 'All', 'streamium' ) . ' ' . strtoupper($category->name); ?></a></li>
											<?php $ChildCats = get_term_children( $category->term_id, $tax);
									            foreach ($ChildCats as $ChildCat) { 
									            	$term = get_term($ChildCat);
									        ?>
												<li><a href="<?php echo esc_url(get_category_link( $term->term_id )); ?>"><?php echo ucwords($term->name); ?></a></li>
									        <?php } ?>
										</ul>
									</li>

								<?php } else { ?>
									<li><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>"><?php echo ucwords($category->name); ?></a></li>
								<?php } } ?>

							<?php else: 

								foreach ( partition($categories, 4)  as $key => $parentCategory ) { ?>

							    	<li class="menu-item-has-children">
								    	
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

												<li class="menu-item-has-children" id="<?php echo $category->slug; ?>">
													<a href="#0"><?php echo ucwords($category->name); ?></a>

													<ul class="is-hidden">
														<li class="go-back"><a href="#0"></a></li>
														<li class="see-all"><a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>"><?php _e( 'All', 'streamium' ); ?> <?php echo ucwords($category->name); ?></a></li>
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

					if ( has_nav_menu( 'streamium-header-menu', 'streamium' ) ) :

						echo str_replace('sub-menu', 'sub-menu is-hidden', wp_nav_menu( array(
						    'echo' => false,
						    'container' => false, 
						    'theme_location' => 'streamium-header-menu',
						  ) )
						);
						
					else :  
						
						printf('<ul id="cd-primary-nav" class="cd-primary-nav is-fixed"><li><a href="#">%1$s</a></li></ul>', __( '!To display a menu here go to Apperance and menus create a menu and select (Display location Header Menu)', 'streamium' ));

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