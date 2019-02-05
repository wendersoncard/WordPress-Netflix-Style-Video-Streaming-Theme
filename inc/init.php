<?php

$incdir = get_template_directory() . '/inc/';


// THEME:
require_once($incdir .'theme/multi-post-thumbnails.php');
require_once($incdir .'theme/meta-boxes.php');
require_once($incdir .'theme/recently-watched.php');
require_once($incdir .'theme/movie-meta.php');
require_once($incdir .'theme/pagination.php');
require_once($incdir .'theme/customizer.php');
require_once($incdir .'theme/reviews.php');
require_once($incdir .'theme/resume.php');
require_once($incdir .'theme/posts.php');
require_once($incdir .'theme/uploader.php');
require_once($incdir .'theme/helpers.php');
require_once($incdir .'theme/series.php'); 
require_once($incdir .'theme/signed.php'); 
require_once($incdir .'theme/social.php');
require_once($incdir .'theme/custom-post-types.php');
require_once($incdir .'theme/api.php');   

// AJAX CALLS:
require_once($incdir .'api/recent.php');
require_once($incdir .'api/custom.php');
require_once($incdir .'api/home.php');
require_once($incdir .'api/cats.php');
require_once($incdir .'api/tax.php');
require_once($incdir .'api/search.php');
require_once($incdir .'api/tag.php');

// Only include if s2member is installed
if ( class_exists( 'WooCommerce' ) ) {
	require_once($incdir .'theme/woocommerce.php');
}

// Only include if woocommerce is installed
if (function_exists('is_protected_by_s2member')) {
	require_once($incdir .'theme/s2member.php');
}