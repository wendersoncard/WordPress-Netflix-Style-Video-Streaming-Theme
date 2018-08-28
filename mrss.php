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

    $genresList = [	"action",
					"adventure",
					"animals",
					"animated",
					"anime",
					"children",
					"comedy",
					"crime",
					"documentary",
					"drama",
					"educational",
					"fantasy",
					"faith",
					"food",
					"fashion",
					"gaming",
					"health",
					"history",
					"horror",
					"miniseries",
					"mystery",
					"nature",
					"news",
					"reality",
					"romance",
					"science",
					"science fiction",
					"sitcom",
					"special",
					"sports",
					"thriller",
					"technology"
				];
	$cats = [];
	foreach ($genresList as $key => $value) {
		$cats[] = [
			"name" => ucfirst($value),
		    "query" => strtolower($value),
		    "order" => "most_popular"
		];
	}

    $json = [
    	"providerName" => "S3Bubble AWS Media Streaming",
	    "lastUpdated" => $datetime->format('c'),
	    "language" => "en-US",
	    /*"categories" => $cats, 
	    "playlists" => [],*/
	    "movies" => [],
	    "series" => [],
	    //"shortFormVideos" =>  [],
	    //"tvSpecials" => []
    ];

	// Only run if user is logged in
    if ($loop->have_posts()):
        while ($loop->have_posts()) : $loop->the_post();

        	$id               = get_the_ID();
        	$title            = substr( strip_tags(get_the_title()), 0, 200);
        	$shortDescription = wp_trim_words( strip_tags(get_the_content()), $num_words = 20, $more = '... ' );
        	$longDescription  = strip_tags(get_the_content());
        	$releaseDate      = get_the_time('c');
    	 	$thumbnail        = false;

    	 	// Roku meta box data
    	 	$videoUrl      = get_post_meta( $post->ID, 's3bubble_roku_url_meta_box_text', true );
    	 	$videoQuality  = get_post_meta( $post->ID, 's3bubble_roku_quality_meta_box_text', true );
    	 	$VideoType     = get_post_meta( $post->ID, 's3bubble_roku_videotype_meta_box_text', true );
    	 	$videoDuration = get_post_meta( $post->ID, 's3bubble_roku_duration_meta_box_text', true );

		    // Allow a extra image to be added
            if (class_exists('MultiPostThumbnails')) {                              
                
                if (MultiPostThumbnails::has_post_thumbnail( get_post_type( get_the_ID() ), 'roku-thumbnail-image', get_the_ID())) { 

                    $thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id( get_post_type( get_the_ID() ), 'roku-thumbnail-image', get_the_ID() );  
                    $thumbnail = wp_get_attachment_image_url( $thumbnail_id,'streamium-roku-thumbnail' ); 

                }                             
             
            }; // end if MultiPostThumbnails 

        	$taxonomy_names = get_post_taxonomies( );
        	$categories = get_the_terms( $id, $taxonomy_names[1] );
        	$genres = [];
        	$cats = [];
        	if ($categories) {
	    		foreach ($categories as $key => $value) {
	    			if (in_array(strtolower($value->name), $genresList)) {
			    		$genres[] = strtolower($value->name);
			    	}
			    	$cats[] = strtolower($value->name);
		    	}
	    	}    	

			// Check for series
			$episodes = get_post_meta(get_the_ID(), 'repeatable_fields' , true);

			if(!empty($episodes)){

				// This is a series

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
				    $v['link'] = get_permalink($postId);
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
							  	"title" => $value2['titles'],
							  	"content" => $videoData2,
							  	"thumbnail" => $value2['thumbnails'],
							  	"episodeNumber" => (int) ($key2+1),
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

				$data = [
				  	"id" => (string) $id,
				  	"title" => $title,
				  	"seasons" => $seasonEpisodes,
				  	"genres" => $genres, 
				    "tags" => $cats, 
				  	"thumbnail" => $thumbnail,
				  	"releaseDate" => get_the_date('Y-m-d'),
				    "shortDescription" => $shortDescription,
				    "longDescription" => $longDescription
				];

				// Only return if the series has episodes with roku data generated
				if(count($episodeObject) > 0){
					
					$json['series'][] = $data;

				}
	
			}else{

				$captions = [
					[
						"url" => "https =>//example.org/cdn/subtitles/1509428502952/sub-fr.srt",
  						"Language" => "fr",
  						"captionType" => "CLOSED_CAPTION"
					]
				];

				// Not a series
				$data = [
	        		"id" => (string) $id,
				    "title" => $title,
				    "content" => [
					  	"dateAdded" => $releaseDate,
					  	"videos" => [
							[
							  "url"=> $videoUrl,
							  "quality"=> $videoQuality,
							  "videoType"=> $VideoType
							]
					  	],
					  	"duration" => (int)$videoDuration,
					  	"captions" => $captions,
					  	"trickPlayFiles" => []
					],
				    "genres" => $genres, 
				    "tags" => $cats, 
				    "thumbnail" => $thumbnail,
				    "releaseDate" => $releaseDate,
				    "shortDescription" => $shortDescription,
				    "longDescription" => $longDescription
	        	];

				// Only run if image is added
				if($thumbnail && $videoUrl && $videoQuality && $VideoType && $videoDuration){

					$json['movies'][] = $data;

				}

			}

        endwhile;
    endif;
	
	header('Content-Type: application/json');
	echo json_encode($json);