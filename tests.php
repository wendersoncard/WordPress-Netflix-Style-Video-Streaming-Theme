<?php

	/*
	 Template Name: Tests Page Template
	 */
	
	// /delete_user_meta( get_current_user_id(), 'user_ratings');

	$get_user_ratings = get_user_meta( get_current_user_id(),'user_ratings', true);

	echo "<pre>";
	print_r($get_user_ratings);

	$likes = get_post_meta( 421, '_pt_likes', true );

	echo "<pre>";
	print_r($likes);

	