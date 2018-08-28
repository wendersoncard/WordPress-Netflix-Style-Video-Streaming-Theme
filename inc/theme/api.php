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
					'thumbnails',
					array(
						'get_callback' => 'streamium_api_thumbnails_get_field',
						'schema'       => null,
					)
				);
			} elseif ( function_exists( 'register_api_field' ) ) {
				register_api_field( $post_type_name,
					'thumbnails',
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

	// Only proceed if the post has a featured image.
	if ( ! empty( $object['featured_media'] ) ) {
		$image_id = (int)$object['featured_media'];
	} elseif ( ! empty( $object['featured_image'] ) ) {
		// This was added for backwards compatibility with < WP REST API v2 Beta 11.
		$image_id = (int)$object['featured_image'];
	} else {
		return null;
	}

	$image = get_post( $image_id );

	if ( ! $image ) {
		return null;
	}

	// BUILD:
	$featured_image['id']            = $image_id;
	$featured_image['alt_text']      = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	$featured_image['caption']       = $image->post_excerpt;
	$featured_image['description']   = $image->post_content;
	$featured_image['media_type']    = wp_attachment_is_image( $image_id ) ? 'image' : 'file';
	$featured_image['media_details'] = wp_get_attachment_metadata( $image_id );
	$featured_image['post']          = ! empty( $image->post_parent ) ? (int) $image->post_parent : null;
	$featured_image['source_url']    = wp_get_attachment_url( $image_id );

	if ( empty( $featured_image['media_details'] ) ) {
		$featured_image['media_details'] = new stdClass;
	} elseif ( ! empty( $featured_image['media_details']['sizes'] ) ) {
		$img_url_basename = wp_basename( $featured_image['source_url'] );
		foreach ( $featured_image['media_details']['sizes'] as $size => &$size_data ) {
			$image_src = wp_get_attachment_image_src( $image_id, $size );
			if ( ! $image_src ) {
				continue;
			}
			$size_data['source_url'] = $image_src[0];
		}
	} elseif ( is_string( $featured_image['media_details'] ) ) {
		// This was added to work around conflicts with plugins that cause
		// wp_get_attachment_metadata() to return a string.
		$featured_image['media_details'] = new stdClass();
		$featured_image['media_details']->sizes = new stdClass();
	} else {
		$featured_image['media_details']['sizes'] = new stdClass;
	}
	return apply_filters( 'streamium_api_thumbnails', $featured_image, $image_id );

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