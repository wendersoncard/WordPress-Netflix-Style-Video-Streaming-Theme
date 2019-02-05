<?php 

	/*
	 	Template Name: Mrss XML Template
	*/

	global $wp;
	
	$title = get_bloginfo('name');
	$link = get_site_url();
	$description  = get_bloginfo('description');
	$lang = "en-us";
	$copyright = "Copyright 2019 S3Bubble";
	$builddate = date(DATE_RFC2822);
	
	header('Content-Type: text/xml');

	// Customize the code below to return the videos and fields for your feed;	
	print('<?xml version="1.0" encoding="UTF-8"?>');
	print('<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom">');
	print('<channel>');
	print('<title>'. $title . '</title>');
	print('<link>'. $link . '</link>');
	print('<description><![CDATA['. $description . ']]></description>');
	print('<language>'. $lang . '</language>');
	print('<copyright>'. $copyright . '</copyright>');
	print('<lastBuildDate>'. $builddate . '</lastBuildDate>');

	print('<image>');	
				
		print('<link>'. $link . '</link>');
		print('<title>'. $title . '</title>');
		print('<url>'. esc_url(get_theme_mod_ssl('streamium_logo')) . '</url>');
		print('<description><![CDATA['. $description . ']]></description>');
		print('<height>114</height>');
		print('<width>114</width>');

	print('</image>');

	print('<atom:link href="' . home_url( $wp->request ) . '" rel="self" type="application/rss+xml"/>');

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
        	$link             = get_the_permalink(get_the_ID());
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

    	 	if($thumbnail && $videoUrl && $videoQuality && $VideoType && $videoDuration){

			    print('<item>');	
				
				print('<title>');
				print_r($title);
				print('</title>');	

				print('<pubDate>');
				print_r(date(DATE_RFC2822,$releaseDate));
				print('</pubDate>');
					
				print('<link>');
				print_r($link);
				print('</link>');
			
				print('<description>');
				print_r($shortDescription);
				print('</description>');

				print('<guid isPermaLink="false">' . $link . '</guid>');

				print('<media:category>All</media:category>');

				$tax = get_post_taxonomies($post->ID);
				print_r($tax);

				$categories = get_the_terms($post->ID, get_theme_mod('streamium_section_input_taxonomy_' . $tax[0], $tax[0]));
				if ( ! empty( $categories ) ) {
					foreach( $categories as $category ) {
						print('<media:category>' . $category->name . '</media:category>');
					} 
				}				
			
				print('<media:content url="' . $videoUrl . '" language="en-us" duration="' . $videoDuration . '.0" medium="video" isDefault="true">');
					print('<media:title type="plain">' . $category->name . '</media:title>');
					print('<media:description type="html">' . $category->name . '</media:description>');
					print('<media:thumbnail url="' . $thumbnail . '" />');
					print('<media:credit role="author" scheme="urn:ebu">Amazon</media:credit>');
					print('<media:copyright url="https://creativecommons.org/licenses/by/4.0/"/>');				
				print('</media:content>');
				
			    print('</item>');

			}
		
		endwhile;
    endif;
	
	print('</channel></rss>');
	
?>