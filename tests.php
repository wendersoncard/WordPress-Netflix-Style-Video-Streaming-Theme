<?php

	/*
	 Template Name: Tests Page Template
	 */

	 $likes = get_post_meta( 231, 'streamium_likes', true );

	 print_r($likes);

	 update_post_meta( 231, 'streamium_likes', 3 );