<?php

/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_prefix() {
    return "api";
}
add_filter( 'rest_url_prefix', 'streamium_api_prefix');
flush_rewrite_rules(true);

/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_series_init() {

	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	foreach ( $post_types as $post_type ) {

		$post_type_name     = $post_type->name;
		$show_in_rest       = ( isset( $post_type->show_in_rest ) && $post_type->show_in_rest ) ? true : false;

		// CHECK IF SHOW REST IS SET ON CREATION:
		if ( $show_in_rest) {

			if ( function_exists( 'register_rest_field' ) ) {
				register_rest_field( $post_type_name,
					'series',
					array(
						'get_callback' => 'streamium_api_add_series_get_field',
						'schema'       => null,
					)
				);
			} elseif ( function_exists( 'register_api_field' ) ) {
				register_api_field( $post_type_name,
					'series',
					array(
						'get_callback' => 'streamium_api_add_series_get_field',
						'schema'       => null,
					)
				);
			}
		}
	}
}

add_action( 'init', 'streamium_api_add_series_init', 12 );


/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_series_get_field( $object, $field_name, $request ) {

	// PARAMS:
	$id    = $object['id'];
	$title = substr( strip_tags(get_the_title($id)), 0, 200);
	$shortDescription = wp_trim_words( strip_tags(get_the_content($id)), $num_words = 20, $more = '... ' );
	$longDescription  = strip_tags(get_the_content($id));
	$releaseDate      = get_the_time('c');
 	$thumbnail        = false;

	// CHECKS:
	if (empty($id)) {
		return null;
	}

	// Check for series
	$episodes = get_post_meta($id, 'repeatable_fields' , true);

	if(!empty($episodes)){

		// Allow a extra image to be added
        if (class_exists('MultiPostThumbnails')) {                              
            
            if (MultiPostThumbnails::has_post_thumbnail( get_post_type( $id ), 'content_tile_landscape', $id)) { 

                $thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id( get_post_type( $id ), 'content_tile_landscape', $id );  
                $thumbnail = wp_get_attachment_image_url( $thumbnail_id,'streamium-roku-thumbnail' ); 

            }                             
         
        }; // end if MultiPostThumbnails 

		// Order the list
		$positions = array();
		foreach ($episodes as $key => $row){
		    $positions[$key] = $row['positions'];
		}
		array_multisort($positions, SORT_ASC, $episodes);

		// Sort the seasons
		$result = array();
		foreach ($episodes as $v) {
		    $seasons = $v['seasons'];
		    if (!isset($result[$seasons])) $result[$seasons] = array();
		    $v['link'] = get_permalink($id);
		    $result[$seasons][] = $v;
		}

		$seasonEpisodes = [];
		foreach ($result as $key => $value) {

        	$episodeObject = [];
        	foreach ($value as $key2 => $value2) {

	        	$videoData2 = [
				  	"dateAdded" => get_the_time('c'),
				  	"videos" => [
						[
						  "url"=> $value2['roku_url'],
						  "quality"=> $value2['roku_quality'],
						  "videoType"=> $value2['roku_type']
						]
				  	],
				  	"duration" => (int)$value2['roku_duration']
				];

	        	if($value2['thumbnails'] && $value2['roku_url'] && $value2['roku_quality'] && $value2['roku_type'] && $value2['roku_duration']){

	        		$episodeObject[] = [
					  	"id" => (string) $id . $value[0]['seasons'] . $value[0]['positions'] . $key2,
					  	"episodeNumber" => (int) ($key2+1),
					  	"title" => $value2['titles'],
					  	"content" => $videoData2,
					  	"thumbnail" => $value2['thumbnails'],
					  	"releaseDate" => get_the_date('Y-m-d'),
					  	"shortDescription" => $value2['descriptions'],
					  	"longDescription" => $value2['descriptions']
					];

				}

        	}

			$seasonEpisodes[] = array(
				'seasonNumber' => (int) $key, 
				'episodes' => $episodeObject, 
				"thumbnail" => $thumbnail,
			);

		}

		$series = [
		  	"id" => (string) $id,
		  	"title" => $title,
		  	"thumbnail" => $thumbnail,
		  	"releaseDate" => get_the_date('Y-m-d'),
		    "shortDescription" => $shortDescription,
		    "longDescription" => $longDescription,
		  	"seasons" => $seasonEpisodes
		];

		return apply_filters( 'streamium_api_series', $series, $id );

	}
	
}

/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_media_init() {

	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	foreach ( $post_types as $post_type ) {

		$post_type_name     = $post_type->name;
		$show_in_rest       = ( isset( $post_type->show_in_rest ) && $post_type->show_in_rest ) ? true : false;

		// CHECK IF SHOW REST IS SET ON CREATION:
		if ( $show_in_rest) {

			if ( function_exists( 'register_rest_field' ) ) {
				register_rest_field( $post_type_name,
					'media',
					array(
						'get_callback' => 'streamium_api_add_media_get_field',
						'schema'       => null,
					)
				);
			} elseif ( function_exists( 'register_api_field' ) ) {
				register_api_field( $post_type_name,
					'media',
					array(
						'get_callback' => 'streamium_api_add_media_get_field',
						'schema'       => null,
					)
				);
			}
		}
	}
}

add_action( 'init', 'streamium_api_add_media_init', 12 );


/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_media_get_field( $object, $field_name, $request ) {

	// PARAMS:
	$postId = $object['id'];

	// CHECKS:
	if (empty($postId)) {
		return null;
	}

	// BUILD:
	$media['url'] = get_post_meta( $postId, 's3bubble_roku_url_meta_box_text', true );
	$media['quality'] = get_post_meta( $postId, 's3bubble_roku_quality_meta_box_text', true );
	$media['videotype'] = get_post_meta( $postId, 's3bubble_roku_videotype_meta_box_text', true );
	$media['duration'] = get_post_meta( $postId, 's3bubble_roku_duration_meta_box_text', true );

	return apply_filters( 'streamium_api_media', $media, $postId );

}

/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_watched_init() {

	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	foreach ( $post_types as $post_type ) {

		$post_type_name     = $post_type->name;
		$show_in_rest       = ( isset( $post_type->show_in_rest ) && $post_type->show_in_rest ) ? true : false;

		// CHECK IF SHOW REST IS SET ON CREATION:
		if ( $show_in_rest) {

			if ( function_exists( 'register_rest_field' ) ) {
				register_rest_field( $post_type_name,
					'watched',
					array(
						'get_callback' => 'streamium_api_add_watched_get_field',
						'schema'       => null,
					)
				);
			} elseif ( function_exists( 'register_api_field' ) ) {
				register_api_field( $post_type_name,
					'watched',
					array(
						'get_callback' => 'streamium_api_add_watched_get_field',
						'schema'       => null,
					)
				);
			}
		}
	}
}

add_action( 'init', 'streamium_api_add_watched_init', 12 );


/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_watched_get_field( $object, $field_name, $request ) {

	// PARAMS:
	$postId = $object['id'];

	// CHECKS:
	if (empty($postId)) {
		return null;
	}

	$progressBar = false;
    if(get_theme_mod( 'streamium_enable_premium' )) {
        $progressBar = (int)get_post_meta( get_the_ID(), 'user_' . get_current_user_id(), true );
    }

	return apply_filters( 'streamium_api_watched', $progressBar, $postId );

}

/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_reviews_init() {

	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	foreach ( $post_types as $post_type ) {

		$post_type_name     = $post_type->name;
		$show_in_rest       = ( isset( $post_type->show_in_rest ) && $post_type->show_in_rest ) ? true : false;

		// CHECK IF SHOW REST IS SET ON CREATION:
		if ( $show_in_rest) {

			if ( function_exists( 'register_rest_field' ) ) {
				register_rest_field( $post_type_name,
					'reviews',
					array(
						'get_callback' => 'streamium_api_add_reviews_get_field',
						'schema'       => null,
					)
				);
			} elseif ( function_exists( 'register_api_field' ) ) {
				register_api_field( $post_type_name,
					'reviews',
					array(
						'get_callback' => 'streamium_api_add_reviews_get_field',
						'schema'       => null,
					)
				);
			}
		}
	}
}

add_action( 'init', 'streamium_api_add_reviews_init', 12 );


/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_reviews_get_field( $object, $field_name, $request ) {

	// PARAMS:
	$postId = $object['id'];

	// CHECKS:
	if (empty($postId)) {
		return null;
	}

	// GET COMMENTS::
    $comments_count = wp_count_comments(get_the_ID());

	return apply_filters( 'streamium_api_reviews', $comments_count->approved, $postId );

}

/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_extra_meta_init() {

	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	foreach ( $post_types as $post_type ) {

		$post_type_name     = $post_type->name;
		$show_in_rest       = ( isset( $post_type->show_in_rest ) && $post_type->show_in_rest ) ? true : false;

		// CHECK IF SHOW REST IS SET ON CREATION:
		if ( $show_in_rest) {

			if ( function_exists( 'register_rest_field' ) ) {
				register_rest_field( $post_type_name,
					'extra_meta',
					array(
						'get_callback' => 'streamium_api_add_extra_meta_get_field',
						'schema'       => null,
					)
				);
			} elseif ( function_exists( 'register_api_field' ) ) {
				register_api_field( $post_type_name,
					'extra_meta',
					array(
						'get_callback' => 'streamium_api_add_extra_meta_get_field',
						'schema'       => null,
					)
				);
			}
		}
	}
}

add_action( 'init', 'streamium_api_add_extra_meta_init', 12 );


/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_extra_meta_get_field( $object, $field_name, $request ) {

	// PARAMS:
	$postId = $object['id'];

	// CHECKS:
	if (empty($postId)) {
		return null;
	}

	$extraMeta = "";
    $streamium_extra_meta = get_post_meta( $postId, 'streamium_premium_meta_box_extra_meta_text', true );
    if ( ! empty( $streamium_extra_meta ) ) {
        $extraMeta = '<h5>' . $streamium_extra_meta . '</h5>';
    }

	return apply_filters( 'streamium_api_extra_meta', $extraMeta, $postId );

}


/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_thumbnails_init() {

	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	foreach ( $post_types as $post_type ) {

		$post_type_name     = $post_type->name;
		$show_in_rest       = ( isset( $post_type->show_in_rest ) && $post_type->show_in_rest ) ? true : false;
		$supports_thumbnail = post_type_supports( $post_type_name, 'thumbnail' );

		// CHECK IF SHOW REST IS SET ON CREATION:
		if ( $show_in_rest && $supports_thumbnail ) {

			if ( function_exists( 'register_rest_field' ) ) {
				register_rest_field( $post_type_name,
					'images',
					array(
						'get_callback' => 'streamium_api_thumbnails_get_field',
						'schema'       => null,
					)
				);
			} elseif ( function_exists( 'register_api_field' ) ) {
				register_api_field( $post_type_name,
					'images',
					array(
						'get_callback' => 'streamium_api_thumbnails_get_field',
						'schema'       => null,
					)
				);
			}
		}
	}

}

add_action( 'init', 'streamium_api_thumbnails_init', 12 );

/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_thumbnails_get_field( $object, $field_name, $request ) {

	// PARAMS:
	$postId = $object['id'];

	// DEFAULTS:
	$thumbnails = [
		'content_tile_landscape' => [
			'url' => 'https://via.placeholder.com/350x150'
		],
		'content_tile_expanded_landscape' => [
			'url' => 'https://via.placeholder.com/350x150'
		],
		'content_tile_portrait' => [
			'url' => 'https://via.placeholder.com/350x150'
		],
		'content_tile_expanded_portrait' => [
			'url' => 'https://via.placeholder.com/350x150'
		],
		'content_tile_roku' => [
			'url' => 'https://via.placeholder.com/350x150'
		]
	];

	if (class_exists('MultiPostThumbnails')) {                              
                    
        if (MultiPostThumbnails::has_post_thumbnail(get_post_type( $postId ), 'content_tile_landscape')) { 
            
            $url = wp_get_attachment_image_url(
				MultiPostThumbnails::get_post_thumbnail_id( 
					get_post_type( 
						$postId 
					), 
					'content_tile_landscape', 
					$postId 
				)
				,'content_tile_landscape'
			);

			$thumbnails['content_tile_landscape'] = [
				'url' => esc_url($url)
			];

        }

        if (MultiPostThumbnails::has_post_thumbnail(get_post_type( $postId ), 'content_tile_expanded_landscape')) { 
            
            $url = wp_get_attachment_image_url(
				MultiPostThumbnails::get_post_thumbnail_id( 
					get_post_type( 
						$postId 
					), 
					'content_tile_expanded_landscape', 
					$postId 
				)
				,'content_tile_expanded_landscape'
			);

			$thumbnails['content_tile_expanded_landscape'] = [
				'url' => esc_url($url)
			];

        }

        if (MultiPostThumbnails::has_post_thumbnail(get_post_type( $postId ), 'content_tile_portrait')) { 
            
            $url = wp_get_attachment_image_url(
				MultiPostThumbnails::get_post_thumbnail_id( 
					get_post_type( 
						$postId 
					), 
					'content_tile_portrait', 
					$postId 
				)
				,'content_tile_portrait'
			);

			$thumbnails['content_tile_portrait'] = [
				'url' => esc_url($url)
			];

        }

        if (MultiPostThumbnails::has_post_thumbnail(get_post_type( $postId ), 'content_tile_expanded_portrait')) { 
            
            $url = wp_get_attachment_image_url(
				MultiPostThumbnails::get_post_thumbnail_id( 
					get_post_type( 
						$postId 
					), 
					'content_tile_expanded_portrait', 
					$postId 
				)
				,'content_tile_expanded_portrait'
			);

			$thumbnails['content_tile_expanded_portrait'] = [
				'url' => esc_url($url)
			];

        }

        if (MultiPostThumbnails::has_post_thumbnail(get_post_type( $postId ), 'content_tile_roku')) { 
            
            $url = wp_get_attachment_image_url(
				MultiPostThumbnails::get_post_thumbnail_id( 
					get_post_type( 
						$postId 
					), 
					'content_tile_roku', 
					$postId 
				)
				,'content_tile_roku'
			);

			$thumbnails['content_tile_roku'] = [
				'url' => esc_url($url)
			];

        }                   
     
    };

	return apply_filters( 'streamium_api_thumbnails', $thumbnails, $postId );

}



/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_genres_mobile_images_init() {

	register_rest_field( 'genres',
		'images',
		array(
			'get_callback' => 'streamium_api_add_genres_mobile_images',
			'schema'       => null,
		)
	);
}

add_action( 'init', 'streamium_api_add_genres_mobile_images_init', 12 );


/**
 * Update the Wordpress api url prefix
 *
 * @return null
 * @author  @sameast
 */
function streamium_api_add_genres_mobile_images( $object, $field_name, $request ) {

	// PARAMS:
	$termId = $object['id'];

	// CHECKS:
	if (empty($termId)) {
		return null;
	}

	$media = [];
	$images = get_term_meta( $termId, 'streamium_re_meta_images', true );
	if(!empty($images)){

	    foreach ($images as $key => $value) {

	        if(isset($value['streamium_meta_images']['id'])){
	            $url = wp_get_attachment_image_src($value['streamium_meta_images']['id'], 'content_tile_roku');
	            $media[] = $url[0];
	        }
	        
	    }
	    
	}

	return apply_filters( 'streamium_api_genres', $media, $termId );

}