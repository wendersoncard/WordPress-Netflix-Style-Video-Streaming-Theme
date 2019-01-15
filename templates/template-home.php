<?php 

	/*
 		Template Name: Homepage Template
 	*/
	get_header(); 

?>
	
	<!-- Preloader -->
    <div class="loader-mask">
        <div class="loader">
            "Loading..."
        </div>
    </div>

	<?php get_template_part('templates/content', 'slider'); ?>

	<section id="browse" class="carousels-wrapper"></section>

<?php get_footer(); ?>