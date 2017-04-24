<?php

	/*
	 Template Name: Tests Page Template
	 */
    
    remove_all_filters('posts_fields');
    remove_all_filters('posts_join');
    remove_all_filters('posts_groupby');
    remove_all_filters('posts_orderby');
    add_filter( 'posts_fields', 'search_distinct' );
	add_filter( 'posts_join','stats_posts_join_view');
	add_filter( 'posts_groupby', 'my_posts_groupby' );
	add_filter( 'posts_orderby', 'edit_posts_orderby' );
	//'cat' => 46,
	 $the_query = new WP_Query( array( 'posts_per_page' => -1, 'ignore_sticky_posts' => true ) );
print_r($the_query->request);

if ( $the_query->have_posts() ) : ?>

	<!-- pagination here -->

	<!-- the loop -->
	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); 
print_r($post);
	?>
		<h2><?php the_title(); ?></h2>
	<?php endwhile; ?>
	<!-- end of the loop -->

	<!-- pagination here -->

	<?php wp_reset_postdata(); ?>

<?php else : ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>