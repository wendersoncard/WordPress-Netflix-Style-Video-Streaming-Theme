<?php 

	/*
	 	Template Name: Mrss XML Template
	*/
	
	$title = "S3Bubble AWS Media Streaming";
	$link = "http://www.brightcove.com/";
	$description  = "S3Bubble AWS Media Streaming";
	$lang = "en-us";
	$copyright = "Copyright 2019 S3Bubble";
	$builddate = date(DATE_RFC2822);
	
	// Customize the code below to return the videos and fields for your feed;	
	print('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>');
	print('<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:bc="https://s3bubble.com" xmlns:dcterms="http://purl.org/dc/terms/">');
	print('<channel>');
	print('<title>'. $title . '</title>');
	print('<link>'. $link . '</link>');
	print('<description><![CDATA['. $description . ']]></description>');
	print('<language>'. $lang . '</language>');
	print('<copyright>'. $copyright . '</copyright>');
	print('<lastBuildDate>'. $builddate . '</lastBuildDate>');

	// globally loop through post types.
	$args = array(
        'posts_per_page' => -1,
        'post_type' => streamium_global_meta(),
        'post_status' => 'publish'
    );

    $loop = new WP_Query($args);
	
	if ($loop->have_posts()):

        while ($loop->have_posts()) : $loop->the_post();

        	// TOP LEVEL DATA:
        	$id               = get_the_ID();
        	$title            = substr( strip_tags(get_the_title()), 0, 200);
        	$shortDescription = wp_trim_words( strip_tags(get_the_content()), $num_words = 20, $more = '... ' );
        	$longDescription  = strip_tags(get_the_content());
        	$releaseDate      = get_the_time('c');
    	 	$thumbnail        = false;

    	 	// EXTRA THUMBNAILS:
            if (class_exists('MultiPostThumbnails')) {                              
                
                if (MultiPostThumbnails::has_post_thumbnail( get_post_type( get_the_ID() ), 'roku-thumbnail-image', get_the_ID())) { 

                    $thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id( get_post_type( get_the_ID() ), 'roku-thumbnail-image', get_the_ID() );  
                    $thumbnail = wp_get_attachment_image_url( $thumbnail_id, 'streamium-roku-thumbnail' ); 

                }                             
             
            }; 

    	 	// ROKU META DATA:
    	 	$videoUrl      = get_post_meta( $post->ID, 's3bubble_roku_url_meta_box_text', true );
    	 	$videoQuality  = get_post_meta( $post->ID, 's3bubble_roku_quality_meta_box_text', true );
    	 	$VideoType     = get_post_meta( $post->ID, 's3bubble_roku_videotype_meta_box_text', true );
    	 	$videoDuration = get_post_meta( $post->ID, 's3bubble_roku_duration_meta_box_text', true );

		    print('<item>');	
			
			print('<title>');
			print_r($title);
			print('</title>');	
				
			print('<link>');
			print_r($videoUrl);
			print('</link>');
		
			print('<description>');
			print_r($shortDescription);
			print('</description>');
			
			print('<pubDate>');
			print_r(date(DATE_RFC2822,$releaseDate));
			print('</pubDate>');
		
			print('<media:player>');
			print_r('height="640"');
			print_r(' width="360"');
			print_r(' url="' . $videoUrl . '"');
			print('</media:player>');
			
			print('<media:keywords>');
			$keywords = "";
			$my_tags = get_the_tags();
			if ( $my_tags ) {
			    foreach ( $my_tags as $tag ) {
			        $keywords = $keywords . ($keywords == "" ? "" : ",") . $tag->name;
			    }
			}
			print_r($keywords);
			print('</media:keywords>');
			
			print('<media:thumbnail>');
			print_r($thumbnail);
			print('</media:thumbnail>');
		
			print('<bc:videoid>');
			print_r($id);
			print('</bc:videoid>');
		
			print('<bc:duration>');
			print_r($videoDuration);
			print('</bc:duration>');
			
		    print('</item>');
		
		endwhile;
    endif;
	
	print('</channel></rss>');
	
?>