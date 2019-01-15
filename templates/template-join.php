<?php

	/*
 		Template Name: Join Template
 	*/

	get_template_part( 'header', 'blank' ); 

    // Start the loop.
    while ( have_posts() ) : the_post();

		$image       = wp_get_attachment_image_url( get_post_thumbnail_id(), 'content_tile_full_width_landscape' );
		$shopPage    = get_permalink( woocommerce_get_page_id( 'shop' ) );
		$accountPage = get_permalink( woocommerce_get_page_id( 'myaccount' ) );

?>
	
		<section class="full-hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)), url(<?php echo esc_url( $image ); ?>)">
			<a class="btn btn-primary home-join-signin" href="<?php echo $accountPage; ?>">SIGN IN</a>
	        <div class="full-hero-inner">
	            <h1><?php the_title(); ?></h1>
	            <p><?php the_content(); ?></p>
	            <a href="<?php echo $shopPage; ?>" class="btn btn-primary btn-lg">JOIN FOR A MONTH FOR FREE</a>
	        </div>
	    </section>

<?php 
	endwhile; 
?>

<?php get_footer(); ?>