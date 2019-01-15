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

	//error_log(json_encode(is_single()));

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
            
            if (MultiPostThumbnails::has_post_thumbnail( get_post_type( $id ), 'roku-thumbnail-image', $id)) { 

                $thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id( get_post_type( $id ), 'roku-thumbnail-image', $id );  
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

	//error_log(json_encode(is_single()));

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

	//error_log(json_encode(is_single()));

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

	//error_log(json_encode(is_single()));

	// PARAMS:
	$postId = $object['id'];

	// CHECKS:
	if (empty($postId)) {
		return null;
	}

	$reviews = get_streamium_likes($postId);

	return apply_filters( 'streamium_api_reviews', $reviews, $postId );

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

	//error_log(json_encode(is_single()));

	// PARAMS:
	$postId = $object['id'];

	// CHECKS:
	if (empty($postId)) {
		return null;
	}

	$extraMeta = "";
    $streamium_extra_meta = get_post_meta( $postId, 'streamium_extra_meta_meta_box_text', true );
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
		'tile' => [
			'url' => 'https://via.placeholder.com/350x150'
		],
		'expanded' => [
			'url' => 'https://via.placeholder.com/350x150'
		],
		'landscape' => [
			'url' => 'https://via.placeholder.com/350x150'
		],
		'roku' => [
			'url' => 'https://via.placeholder.com/350x150'
		]
	];

	if (has_post_thumbnail( $postId ) ){
		
		$tile = get_the_post_thumbnail_url( $postId, 'streamium-video-tile' );
		$thumbnails['tile'] = [
			'url' => esc_url($tile)
		];

		$thumbnails['expanded'] = [
			'url' => esc_url($tile)
		];

	}

	if (class_exists('MultiPostThumbnails')) {                              
                    
        if (MultiPostThumbnails::has_post_thumbnail(get_post_type( $postId ), 'tile-expanded-image')) { 
            
            $expanded = wp_get_attachment_image_url(
				MultiPostThumbnails::get_post_thumbnail_id( 
					get_post_type( 
						$postId 
					), 
					'tile-expanded-image', 
					$postId 
				)
				,'streamium-video-tile-expanded'
			);

			$thumbnails['expanded'] = [
				'url' => esc_url($expanded)
			];

        }

        if (MultiPostThumbnails::has_post_thumbnail(get_post_type( $postId ), 'large-landscape-image')) { 
            
            $landscape = wp_get_attachment_image_url(
				MultiPostThumbnails::get_post_thumbnail_id( 
					get_post_type( 
						$postId 
					), 
					'large-landscape-image', 
					$postId 
				)
				,'streamium-video-tile-large-expanded'
			);

			$thumbnails['landscape'] = [
				'url' => esc_url($landscape)
			];

        }  

        if (MultiPostThumbnails::has_post_thumbnail(get_post_type( $postId ), 'roku-thumbnail-image')) { 
            
            $roku = wp_get_attachment_image_url(
				MultiPostThumbnails::get_post_thumbnail_id( 
					get_post_type( 
						$postId 
					), 
					'roku-thumbnail-image', 
					$postId 
				)
				,'streamium-roku-thumbnail'
			);

			$thumbnails['roku'] = [
				'url' => esc_url($roku)
			];

        }                        
     
    };

	return apply_filters( 'streamium_api_thumbnails', $thumbnails, $postId );

}

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function mrss_generate_key() {

   // Generate a random salt
    $salt = base_convert(bin2hex(random_bytes(64)), 16, 36);

    // If an error occurred, then fall back to the previous method
    if ($salt === FALSE)
    {
        $salt = hash('sha256', time() . mt_rand());
    }

    $new_key = substr($salt, 0, 40);
    
	echo json_encode(
    	array(
    		'status' => true,
    		'message' => 'Success',
    		'key' => $new_key,
    	)
    );      

    die(); 

}

add_action(
	"wp_ajax_mrss_generate_key", 
	"mrss_generate_key" 
);