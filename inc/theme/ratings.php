<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Ratings class for admin
 *
 * @return bool
 * @author  @sameast
 */
class Streamium_Ratings_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Customer', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Customers', 'sp' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );

	}

	/**
	 * Retrieve customer’s data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_ratings( $per_page = 25, $page_number = 1 ) {

	  global $wpdb;

	  $sql = "SELECT * FROM {$wpdb->prefix}streamium_reviews";

	  if ( ! empty( $_REQUEST['orderby'] ) ) {
	    $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
	    $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
	  }

	  $sql .= " LIMIT $per_page";

	  $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


	  $result = $wpdb->get_results( $sql, 'ARRAY_A' );

	  return $result;

	}

	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_rating( $id ) {

	  global $wpdb;

	  $wpdb->delete(
	    "{$wpdb->prefix}streamium_reviews",
	    [ 'id' => $id ],
	    [ '%d' ]
	  );

	}

	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function update_rating( $id ) {

	  	global $wpdb;

		$wpdb->update( 
			"{$wpdb->prefix}streamium_reviews", 
			array( 
				'state' => 1,	// string
			), 
			array( 'id' => $id ), 
			array( 
				'%d'	// value2
			), 
			array( '%d' ) 
		);

	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
	  global $wpdb;

	  $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}streamium_reviews";

	  return $wpdb->get_var( $sql );
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
	  _e( 'No customers avaliable.', 'sp' );
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

	  // create a nonce
	  $delete_nonce = wp_create_nonce( 'sp_delete_rating' );

	  $title = '<strong>' . $item['message'] . '</strong>';

	  $actions = [
	    'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
	  ];

	  return $title . $this->row_actions( $actions );
	}

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
	  switch ( $column_name ) {
	    case 'user_id':
	    
	    	$user_info = get_userdata( $item[ $column_name ]);
	    	return $user_info->user_login;
	    
	    case 'post_id':
	    
	    	return get_the_title( $item[ $column_name ] );
	    
	    case 'message':
	    
	    	return $item[ $column_name ];
	    
	    case 'state':

	        // create a nonce
	  		$approve_nonce = wp_create_nonce( 'sp_update_rating' );
	  		if($item[ $column_name ]){
	  			return 'Approved';
	  		}else{
	  			return sprintf( '<a href="?page=%s&action=%s&rating=%s&_wpnonce=%s">Approve</a>', esc_attr( $_REQUEST['page'] ), 'update', absint( $item['id'] ), $approve_nonce );
	  		}
	       
	    default:
	      return print_r( $item, true ); //Show the whole array for troubleshooting purposes
	  }
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
	  return sprintf(
	    '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
	  );
	}

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
	  $columns = [
	    'cb'      => '<input type="checkbox" />',
	    'user_id'    => __( 'User', 'sp' ),
	    'post_id'    => __( 'Movie', 'sp' ),
	    'message' => __( 'Message', 'sp' ),
	    'state'    => __( 'State', 'sp' )
	  ];

	  return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
	  $sortable_columns = array(
	    'user_id' => array( 'user_id', true ),
	    'post_id' => array( 'post_id', true ),
	    'message' => array( 'message', false )
	  );

	  return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
	  $actions = [
	    'bulk-delete' => 'Delete'
	  ];

	  return $actions;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

	  $this->_column_headers = $this->get_column_info();

	  /** Process bulk action */
	  $this->process_bulk_action();

	  $per_page     = $this->get_items_per_page( 'customers_per_page', 5 );
	  $current_page = $this->get_pagenum();
	  $total_items  = self::record_count();

	  $this->set_pagination_args( [
	    'total_items' => $total_items, //WE have to calculate the total number of items
	    'per_page'    => $per_page //WE have to determine how many items to show on a page
	  ] );


	  $this->items = self::get_ratings( $per_page, $current_page );
	}

	public function process_bulk_action() {

		if ( 'update' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

		    if ( ! wp_verify_nonce( $nonce, 'sp_update_rating' ) ) {
		      die( 'Go get a life script kiddies' );
		    }
		    else {

		      self::update_rating( absint( $_GET['rating'] ) );

		    }

		}

	  //Detect when a bulk action is being triggered...
	  if ( 'delete' === $this->current_action() ) {

	  	// In our file that handles the request, verify the nonce.
		$nonce = esc_attr( $_REQUEST['_wpnonce'] );

	    if ( ! wp_verify_nonce( $nonce, 'sp_delete_rating' ) ) {
	      die( 'Go get a life script kiddies' );
	    }
	    else {

	      self::delete_rating( absint( $_GET['customer'] ) );

	    }

	  }

	  // If the delete bulk action is triggered
	  if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
	       || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
	  ) {

	    $delete_ids = esc_sql( $_POST['bulk-delete'] );

	    // loop over the array of record IDs and delete them
	    foreach ( $delete_ids as $id ) {
	      self::delete_rating( $id );

	    }

	  }
	}

}

/**
 * Streamium Rating create menu
 *
 * @return bool
 * @author  @sameast
 */
class Streamium_Ratings {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $ratings_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {

		$hook = add_menu_page(
			'Streamium ratings',
			'Ratings',
			'manage_options',
			'wp_list_table_class',
			[ $this, 'plugin_settings_page' ]
		);

		add_action( "load-$hook", [ $this, 'screen_option' ] );

	}

	/**
	* Screen options
	*/
	public function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => 'Customers',
			'default' => 25,
			'option'  => 'customers_per_page'
		];

		add_screen_option( $option, $args );

		$this->ratings_obj = new Streamium_Ratings_List();
	}

	/**
	* Plugin settings page
	*/
	public function plugin_settings_page() {
		?>
		<div class="wrap">
			<h2>Streamium Ratings</h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->ratings_obj->prepare_items();
								$this->ratings_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

add_action( 'after_setup_theme', function () {
	Streamium_Ratings::get_instance();
} );

/**
 * Add ratings to the database
 *
 * @return bool
 * @author  @sameast
 */
global $streamium_reviews_db_version;
$streamium_reviews_db_version = '1.0';
function streamium_reviews_db_install() {

	global $wpdb;
	global $streamium_reviews_db_version;

	$table_name = $wpdb->prefix . 'streamium_reviews';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		user_id int NOT NULL,
		post_id int NOT NULL,
		message text NOT NULL,
		state boolean NOT NULL DEFAULT 0,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'streamium_reviews_db_version', $streamium_reviews_db_version );

}

add_action("after_switch_theme", "streamium_reviews_db_install");


function checkIfArrayKeyValueExists($array, $key, $val) {
    foreach ($array as $item)
        if (isset($item[$key]) && $item[$key] == $val)
            return true;
    return false;
}

/**
 * Streamium rating ajax
 *
 * @return bool
 * @author  @sameast
 */
function streamium_likes() {

	global $wpdb;

	// Get params
	$userId = get_current_user_id();
	$postId = $_REQUEST['post_id'];
	$message = $_REQUEST['message'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'streamium_likes_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }

    // Check if user is logged in
    if ( !is_user_logged_in() ) {

    	echo json_encode(
	    	array(
	    		'error' => true,
	    		'message' => 'You must be logged in to like or dislike' 
	    	)
	    );

	    die();

    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$errors = false;
    	$errorMessage = "";

    	$table_name = $wpdb->prefix . 'streamium_reviews';

    	$checkIfExists = $wpdb->get_row( "SELECT * FROM $table_name WHERE user_id = $userId AND post_id = $postId", ARRAY_N );
    	if ( null !== $checkIfExists ) {

    		$errors = true;
    		$errorMessage = "You have already reviewed this video";

    	}else{

    		$wpdb->insert( 
				$table_name, 
				array( 
					'user_id' => $userId, 
					'post_id' => $postId,
					'message' => $message,
					'time' => current_time('mysql', 1)    
				), 
				array(
					'%d',
					'%d', 
					'%s', 
					'%s' 
				) 
			);
    	}

    	if($errors){

    		echo json_encode(
		    	array(
		    		'error' => true,
		    		'message' => $errorMessage
		    	)
		    );

		}else{

			echo json_encode(
		    	array(
		    		'error' => false,
		    		'likes' => get_streamium_likes($postId),
		    		'message' => 'Successfully added your rating' 
		    	)
		    );

		}

        die();

    }
    else {
        
        wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
        exit();

    }

}

add_action( 'wp_ajax_nopriv_streamium_likes', 'streamium_likes' );
add_action( 'wp_ajax_streamium_likes', 'streamium_likes' );

/**
 * Add time from now to reviews
 *
 * @return bool
 * @author  @sameast
 */
function time2str($ts)
{
    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;
    if($diff == 0)
        return 'now';
    elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'Yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        return date('F Y', $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'in an hour';
            if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        return date('F Y', $ts);
    }
}

/**
 * Ajax sidebar get reviews
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_reviews() {

	global $wpdb;

	// Get params
	$postId = $_REQUEST['post_id'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'streamium_likes_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }

    // Check if user is logged in
    if ( !is_user_logged_in() ) {

    	echo json_encode(
	    	array(
	    		'error' => true,
	    		'message' => 'You must be logged in to view reviews' 
	    	)
	    );

	    die();

    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$table_name = $wpdb->prefix . 'streamium_reviews';

    	$getReviews = $wpdb->get_results( 
			"
			SELECT *
			FROM $table_name
			WHERE post_id = $postId
			AND state = 1 
			"
		);

		if ( $getReviews )
		{

			$buildGetReviews = [];
			foreach ( $getReviews as $post )
			{
				
				$userInfo = get_userdata($post->user_id);
				array_push($buildGetReviews, array(
		    		'username' => $userInfo->user_login,
		    		'avatar' => get_avatar_url($post->user_id, array( "size" => 64 ) ),
		    		'post_id' => $post->post_id,
		    		'message' => $post->message,
		    		'time' => time2str($post->time)
		    	));

			}

			echo json_encode(
		    	array(
		    		'error' => false,
		    		'title' => get_the_title($postId),
		    		'data' => $buildGetReviews,
		    		'message' => 'Successfully added your rating' 
		    	)
		    );

		}
		else
		{
			
			echo json_encode(
		    	array(
		    		'error' => true,
		    		'message' => 'No reviews' 
		    	)
		    );

		}

    	

        die();

    }
    else {
        
        wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
        exit();

    }

}

add_action( 'wp_ajax_nopriv_streamium_get_reviews', 'streamium_get_reviews' );
add_action( 'wp_ajax_streamium_get_reviews', 'streamium_get_reviews' );

/**
 * Get like count for post
 *
 * @return bool
 * @author  @sameast
 */
function get_streamium_likes($post_id) {

	global $wpdb;
	$table_name = $wpdb->prefix . 'streamium_reviews';
    $getReviews = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE post_id = $post_id");
    return $getReviews;

}

/**
 * Include streamium like scripts
 *
 * @return bool
 * @author  @sameast
 */
function streamium_likes_scripts() {
    if( !is_single() ) {
 
        wp_enqueue_style( 'reviews', get_template_directory_uri() . '/dist/css/reviews.min.css' );
    	wp_enqueue_script( 'reviews', get_template_directory_uri() . '/dist/js/reviews.min.js', array('jquery'), '1.0', true );

    }
}

add_action( 'wp_enqueue_scripts', 'streamium_likes_scripts' );