<?php 

	/*
	 	Template Name: Mrss XML Template
	*/

	// Brightcove sample for MRSS feed
	
	// This is just a sample to get you started. You can customize further as your requirements
	// grow.
	// The following is a list of requirements and conditions in order for this podcast feed
	// to function properly;
	//    1) You must have a Pro or Enterprise level Video Cloud Account.
	//    2) You will need to contact Brightcove Support to request an API READ Token with URL 
	//       access, if you don't have one already.
	
	// Please customize the variables below:
	
	// This is the title of the podcast itself.	
	$title = "Video Cloud Test Feed";
	// This is your Media API READ token with URL Access. This allows you to access the media files and not just the metadata.
	$token = "WDGO_XdKqXVJRVGtrNuGLxCYDNoR-SvA5yUqX2eE6KjgefOxRzQilw..";
	// This is a link to where the MRSS feed can be found.
	$link = "http://www.brightcove.com/";
	// This is a description of this iTunes Feed.
	$description  = "Description of the Video Cloud Playlist Feed";
	// This is the language you display for this podcast.
	$lang = "en-us";
	// This is the copyright information.
	$copyright = "Copyright 2014 Brightcove Inc";
	// This is the build date.
	$builddate = date(DATE_RFC2822);
	
	// Customize the code below to return the videos and fields for your feed;	
	print('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>');
	print('<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:bc="http://www.brightcove.tv/link" xmlns:dcterms="http://purl.org/dc/terms/">');
	print('<channel>');
	print('<title>'. $title . '</title>');
	print('<link>'. $link . '</link>');
	print('<description><![CDATA['. $description . ']]></description>');
	print('<language>'. $lang . '</language>');
	print('<copyright>'. $copyright . '</copyright>');
	print('<lastBuildDate>'. $builddate . '</lastBuildDate>');
	
	foreach($returndata->items as $items)
	{
	    print('<item>');	
		
		print('<title>');
		print_r($items->{"name"});
		print('</title>');	
			
		print('<link>');
		print_r($items->{"videoFullLength"}->{"url"});
		print('</link>');
	
		print('<description>');
		print_r($items->{"shortDescription"});
		print('</description>');
		
		print('<pubDate>');
		print_r(date(DATE_RFC2822,(($items->{"publishedDate"})/1000)));
		print('</pubDate>');
	
		print('<media:player>');
		print_r('height="' . $items->{"videoFullLength"}->{"frameHeight"} . '"');
		print_r(' width="' . $items->{"videoFullLength"}->{"frameWidth"} . '"');
		print_r(' url="' . $items->{"videoFullLength"}->{"url"} . '"');
		print('</media:player>');
		
		print('<media:keywords>');
		$keywords = "";
		foreach($items->tags as $tags)
		{
			$keywords = $keywords . ($keywords == "" ? "" : ",") . $tags;
		}
		print_r($keywords);
		print('</media:keywords>');
		
		print('<media:thumbnail>');
		print_r($items->{"thumbnailURL"});
		print('</media:thumbnail>');
	
		print('<bc:videoid>');
		print_r($items->{"id"});
		print('</bc:videoid>');
	
		print('<bc:duration>');
		print_r($items->{"videoFullLength"}->{"videoDuration"});
		print('</bc:duration>');
		
	    print('</item>');
	}
	
	print('</channel></rss>');
	
?>