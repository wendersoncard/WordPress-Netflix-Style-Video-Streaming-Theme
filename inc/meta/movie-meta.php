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

function synopis_multi_meta_starring( ) {
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

add_action( 'synopis_multi_meta', 'synopis_multi_meta_starring', 1, 0 );


function synopis_multi_meta_genre() {

	$query = get_post_taxonomies( get_the_ID() );
	$tax = isset($query[1]) ? $query[1] : "";
	$categories = get_terms( $tax, array('hide_empty' => false) );

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

add_action( 'synopis_multi_meta', 'synopis_multi_meta_genre', 2, 0 );

function synopis_multi_meta_release(){

	echo '<li class="synopis-meta-spacer">Released: <a href="/?s=all&date=' . get_the_date('Y/m/d') . '">' . get_the_date() . '</a></li>';

}

add_action( 'synopis_multi_meta', 'synopis_multi_meta_release', 3, 0 );

function synopis_video_progressbar(){

	if(is_user_logged_in() && get_theme_mod( 'streamium_enable_premium' )) {
		$userId = get_current_user_id();
    	$percentageWatched = get_post_meta( get_the_ID(), 'user_' . $userId, true );
		$episodes = get_post_meta(get_the_ID(), 'repeatable_fields' , true);
		if(!empty($episodes)) {

			

		}else{

			echo '<div class="progress tile_progress"><div class="progress-bar" role="progressbar" aria-valuenow="' . $percentageWatched . '" aria-valuemin="0" aria-valuemax="100" style="width:' . $percentageWatched . '%"></div></div>';
			
		}

	}

}

add_action( 'synopis_video_progress', 'synopis_video_progressbar', 0, 0 );

function streamium_video_payment_callback(){

	global $post;

	if($post->premium) : ?>
		<div class="tile_payment_details">
			<div class="tile_payment_details_inner">
				<h2>Available on <?php echo str_replace(array("_"), " ", $post->plans[0]); ?></h2>
			</div>
		</div> 
	<?php endif; ?>
	<?php if (function_exists('is_protected_by_s2member')) :
		$check = is_protected_by_s2member(get_the_ID());
		if($check) : ?>
		<div class="tile_payment_details">
			<div class="tile_payment_details_inner">
				<h2>Available on <?php 
					$comma_separated = implode(",", $check);
					echo "<br>plan " . $comma_separated; 
				?></h2>
			</div>
		</div>
	<?php endif; endif;

}

add_action( 'streamium_video_payment', 'streamium_video_payment_callback', 0, 0 );