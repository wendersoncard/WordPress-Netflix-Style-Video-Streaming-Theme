<?php

	/*
	 Template Name: Tests Page Template
	 */

	
echo get_the_category_list(421);
	 // Cats
	 $buildMeta = "";
				$categories = get_the_category(421);
				$genres = "Genres: ";
				if ($categories) {
					$numItems = count($categories);
					$i = 0;
				  	foreach($categories as $cat) {

				  		$genres .= '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . $cat->name . '</a>';
				  		if(++$i !== $numItems) {
				    		$genres .= ', ';
				  		}

				  	}
				  	$buildMeta .= '<li class="synopis-meta-spacer">' . $genres . '</li>';
				}
				print_r($buildMeta);