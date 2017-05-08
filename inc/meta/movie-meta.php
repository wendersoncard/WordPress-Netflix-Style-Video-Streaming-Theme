<?php

function synopis_meta_starring( ) {
   // do stuff here
	$posttags = get_the_tags();
	$staring = "Staring: ";
	if ($posttags) {
		$numItems = count($posttags);
		$i = 0;
	  	foreach($posttags as $tag) {

		  	$staring .= '<a href="/?s=' . esc_html( $tag->name ) . '">' . $tag->name . '</a>';
		  	if(++$i !== $numItems) {
	    		$staring .= ', ';
	  		}

	    }
	    echo '<li class="synopis-meta-spacer">' . $staring . '</li>';
	}
	
}

add_action( 'synopis_meta', 'synopis_meta_starring', 1, 0 );


function synopis_meta_genre( ) {

	$categories = get_the_category();
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
	  	echo '<li class="synopis-meta-spacer">' . $genres . '</li>';
	}

}

add_action( 'synopis_meta', 'synopis_meta_genre', 2, 0 );

function synopis_meta_release( ) {

	echo '<li class="synopis-meta-spacer">Released: <a href="/?s=all&date=' . get_the_date('Y/m/d') . '">' . get_the_date() . '</a></li>';

}

add_action( 'synopis_meta', 'synopis_meta_release', 3, 0 );

function synopis_tv_meta_starring( ) {
   // do stuff here
	$posttags = get_the_tags();
	$staring = "Staring: ";
	if ($posttags) {
		$numItems = count($posttags);
		$i = 0;
	  	foreach($posttags as $tag) {

		  	$staring .= '<a href="/?s=' . esc_html( $tag->name ) . '">' . $tag->name . '</a>';
		  	if(++$i !== $numItems) {
	    		$staring .= ', ';
	  		}

	    }
	    echo '<li class="synopis-meta-spacer">' . $staring . '</li>';
	}
	
}

add_action( 'synopis_tv_meta', 'synopis_tv_meta_starring', 1, 0 );


function synopis_tv_meta_genre( ) {

	$categories = get_terms( 'programs', array('hide_empty' => false) );
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
	  	echo '<li class="synopis-meta-spacer">' . $genres . '</li>';
	}

}

add_action( 'synopis_tv_meta', 'synopis_tv_meta_genre', 2, 0 );

function synopis_tv_meta_release( ) {

	echo '<li class="synopis-meta-spacer">Released: <a href="/?s=all&date=' . get_the_date('Y/m/d') . '">' . get_the_date() . '</a></li>';

}

add_action( 'synopis_tv_meta', 'synopis_tv_meta_release', 3, 0 );