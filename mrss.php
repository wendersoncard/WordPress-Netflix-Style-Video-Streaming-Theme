<?php

	/*
	 Template Name: Mrss Template
	 */

	// globally loop through post types.
	$args = array(
        'posts_per_page' => -1,
        'post_type' => array('movie', 'tv','sport','kid','stream'),
        'post_status' => 'publish'
    );
    $loop = new WP_Query($args);

    // Latest build update
    $datetime = new DateTime();

    $json = [
    	"providerName" => "S3Bubble AWS Media Streaming",
	    "lastUpdated" => $datetime->format('c'),
	    "language" => "en",
	   /*"categories" => [
		    "name" => "S3Bubble Videos",
		    "query" => "s3bubble",
		    "order" => "most_popular"
		],
	    "playlists" => [],*/
	    "movies" => [],
	    "series" => [],
	    "shortFormVideos" =>  [],
	    "tvSpecials" => []
    ];

	// Only run if user is logged in
    if ($loop->have_posts()):
        while ($loop->have_posts()) : $loop->the_post();

        	$id               = get_the_ID();
        	$title            = wp_trim_words( strip_tags(get_the_title()), $num_words = 10, $more = '... ' );
        	$shortDescription = wp_trim_words( strip_tags(get_the_content()), $num_words = 20, $more = '... ' );
        	$longDescription  = strip_tags(get_the_content());
        	$releaseDate      = get_the_time('c');

    	 	$thumbnail   = wp_get_attachment_image_url( get_post_thumbnail_id(), 'streamium-roku-thumbnail' );

		    // Allow a extra image to be added
            if (class_exists('MultiPostThumbnails')) {                              
                
                if (MultiPostThumbnails::has_post_thumbnail( get_post_type( get_the_ID() ), 'large-landscape-image', get_the_ID())) { 

                    $thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id( get_post_type( get_the_ID() ), 'large-landscape-image', get_the_ID() );  
                    $thumbnail = wp_get_attachment_image_url( $thumbnail_id,'streamium-roku-thumbnail' ); 

                }                             
             
            }; // end if MultiPostThumbnails 

        	$taxonomy_names = get_post_taxonomies( );
        	$categories = get_the_terms( $id, $taxonomy_names[1] );
        	$genres = [];
        	if ($categories) {
	    		foreach ($categories as $key => $value) {
		    		$genres[] = strtolower($value->name);
		    	}
	    	}    	
 			
 			$data = [
        		"id" => (string) $id,
			    "title" => $title,
			    "content" => [
				  	"dateAdded" => $releaseDate,
				  	"videos" => [
						[
						  "url"=> "https://s3bubble-documentation.s3.amazonaws.com/test.mp4",
						  "quality"=> "FHD",
						  "videoType"=> "MP4"
						]
				  	],
				  	"trickPlayFiles" => [
	
				  	],
				  	"duration" => 1290
				],
			    "genres" => "adventure", //$genres,
			    "thumbnail" => $thumbnail,
			    "releaseDate" => $releaseDate,
			    "shortDescription" => $shortDescription,
			    "longDescription" => $longDescription
        	];

        	

        	$posttags = get_the_tags();
 			$tags = [];
			if ($posttags) {
			  	foreach($posttags as $tag) {
			    	$tags[] = strtolower($tag->name); 
			  	}
			  	$data['tags'] = $tags;
			}

			$json['movies'][] = $data;


        endwhile;
    endif;
	
	header('Content-Type: application/json');
	echo json_encode($json);