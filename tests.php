<?php

	/*
	 Template Name: Tests Page Template
	 */

	$taxUrls  = get_theme_mod( 'streamium_section_input_taxonomy_movies', 'movies' );

	echo "<pre>";
	print_r($taxUrls);
	
	$query = get_post_taxonomies( 777 );
	
	echo "<pre>";
	print_r($query);

	$terms = get_the_terms( 777, 'movies' );

	echo "<pre>";
	print_r($terms);

	$categories = get_terms( 'movies', array('hide_empty' => false) );

	echo "<pre>";
	print_r($categories);