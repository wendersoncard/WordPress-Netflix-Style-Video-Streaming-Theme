<?php get_header(); ?>

	<header class="cd-main-header fixed">

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

	<main class="cd-main-content page-template-light-background">
		
		<div class="main-spacer"></div>
		
		<div class="container">
			<div class="row">

				<?php if ( ! is_active_sidebar( 'forum-sidebar' ) ) : ?>
					<div class="col-sm-12 col-xs-12">
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						 	<?php the_content(); ?>					 

						<?php endwhile; else : ?>

						 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="col-sm-9 col-xs-12">
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						 	<?php the_content(); ?>					 

						<?php endwhile; else : ?>

						 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

						<?php endif; ?>
					</div>
					<div class="col-sm-3 col-xs-12">
						<?php dynamic_sidebar('forum-sidebar'); ?>
					</div>
				<?php endif; ?>
				
			</div>
		</div>

		<div class="main-spacer"></div>

	</main><!--/.main content-->
	
 <?php get_footer(); ?>