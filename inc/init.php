<?php

$incdir = get_template_directory() . '/inc/';


/*-----------------------------------------------------------------------------------*/
/*	Load Theme Specific Helpers
/*-----------------------------------------------------------------------------------*/

require_once($incdir .'meta/meta-boxes.php');
require_once($incdir .'meta/recently-watched.php');
require_once($incdir .'theme/pagination.php');
require_once($incdir .'theme/customizer.php');
require_once($incdir .'theme/woocommerce.php');
require_once($incdir .'theme/helpers.php');