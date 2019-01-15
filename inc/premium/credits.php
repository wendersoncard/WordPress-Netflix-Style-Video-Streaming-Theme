<?php

class Credits {

    protected $_already_saved = false;  # Used to avoid saving twice

    public function __construct() {
        $this->do_initialize();
    }

    protected function do_initialize() {

        add_action(
            'save_post',
            array( $this, 'save_meta_box_data' ),
            10,
            2
        );

       
        add_action(
            'add_meta_boxes',
            array( $this, 'setup_book_boxes' )
        );

    }

    # Grab all posts of the specified type
    # Returns an array of post objects
    protected function get_all_of_post_type( $type_name = '') {
        $items = array();
        if ( !empty( $type_name ) ) {
            $args = array(
                'post_type' => "{$type_name}",
                'posts_per_page' => -1,
                'order' => 'ASC',
                'orderby' => 'title'
            );
            $results = new \WP_Query( $args );
            if ( $results->have_posts() ) {
                while ( $results->have_posts() ) {
                    $items[] = $results->next_post();
                }
            }
        }
        return $items;
    }

    # Get array of book ids for a particular author id
    protected function get_author_content_ids( $author_id = 0 ) {
        $ids = array();
        if ( 0 < $author_id ) {
            $args = array(
                'post_type' => 'content',
                'posts_per_page' => -1,
                'order' => 'ASC',
                'orderby' => 'title',
                'meta_query' => array(
                    array(
                        'key' => '_credit_id',
                        'value' => (int)$author_id,
                        'type' => 'NUMERIC',
                        'compare' => '='
                    )
                )
            );
            $results = new \WP_Query( $args );
            if ( $results->have_posts() ) {
                while ( $results->have_posts() ) {
                    $item = $results->next_post();
                    if ( !in_array($item->ID, $ids) ) {
                        $ids[] = $item->ID;
                    }
                }
            }
        }
        return $ids;
    }

    public function setup_book_boxes( $post ) {
        add_meta_box(
            'book_related_authors_box',
            __('Related Credits', 'language'),
            array( $this, 'draw_book_authors_box' ),
            'content',
            'side',
            'default'
        );
    }

    public function draw_book_authors_box( $post ) {

        $all_authors = $this->get_all_of_post_type( 'credits' );

        $linked_credit_ids = $this->get_book_credit_ids( $post->ID );

        if ( 0 == count($all_authors) ) {
            $choice_block = '<p>No authors found in the system.</p>';
        } else {
            $choices = array();
            foreach ( $all_authors as $author ) {
                $checked = ( in_array( $author->ID, $linked_credit_ids ) ) ? ' checked="checked"' : '';

                $display_name = esc_attr( $author->post_title );
                $choices[] = <<<HTML
<label><input type="checkbox" name="credit_ids[]" value="{$author->ID}" {$checked}/> {$display_name}</label><br/>
HTML;

            }
            $choice_block = implode("\r\n", $choices);
        }

        # Make sure the user intended to do this.
        wp_nonce_field(
            "updating_{$post->post_type}_meta_fields",
            $post->post_type . '_meta_nonce'
        );

        echo $choice_block;
    }


    # Grab all properties related to a specific development area
    # Returns an array of property post ids
    protected function get_book_credit_ids( $book_id = 0 ) {
        $ids = array();
        if ( 0 < $book_id ) {
            $matches = get_post_meta( $book_id, '_credit_id', false);
            if ( 0 < count($matches) ) {
                $ids = $matches;
            }
        }
        return $ids;
    }


    public function save_meta_box_data( $post_id = 0, $post = null ) {

        $do_save = true;

        $allowed_post_types = array(
            'content',
            'credits'
        );

        # Do not save if we have already saved our updates
        if ( $this->_already_saved ) {
            $do_save = false;
        }

        # Do not save if there is no post id or post
        if ( empty($post_id) || empty( $post ) ) {
            $do_save = false;
        } else if ( ! in_array( $post->post_type, $allowed_post_types ) ) {
            $do_save = false;
        }

        # Do not save for revisions or autosaves
        if (
            defined('DOING_AUTOSAVE')
            && (
                is_int( wp_is_post_revision( $post ) )
                || is_int( wp_is_post_autosave( $post ) )
            )
        ) {
            $do_save = false;
        }

        # Make sure proper post is being worked on
        if ( !array_key_exists('post_ID', $_POST) || $post_id != $_POST['post_ID'] ) {
            $do_save = false;
        }

        # Make sure we have the needed permissions to save [ assumes both types use edit_post ]
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            $do_save = false;
        }

        # Make sure the nonce and referrer check out.
        $nonce_field_name = $post->post_type . '_meta_nonce';
        if ( ! array_key_exists( $nonce_field_name, $_POST) ) {
            $do_save = false;
        } else if ( ! wp_verify_nonce( $_POST["{$nonce_field_name}"], "updating_{$post->post_type}_meta_fields" ) ) {
            $do_save = false;
        } else if ( ! check_admin_referer( "updating_{$post->post_type}_meta_fields", $nonce_field_name ) ) {
            $do_save = false;
        }

        if ( $do_save ) {
            switch ( $post->post_type ) {
                case "content":
                    $this->handle_book_meta_changes( $post_id, $_POST );
                    break;
                case "credits":
                    $this->handle_author_meta_changes( $post_id, $_POST );
                    break;
                default:
                    # We do nothing about other post types
                    break;
            }

            # Note that we saved our data
            $this->_already_saved = true;
        }
        return;
    }

    # Authors can be linked to multiple books
    # Notice that we are editing book meta data here rather than author meta data
    protected function handle_author_meta_changes( $post_id = 0, $data = array() ) {

        # META BOX - Details
        $current_details = $this->get_author_details_meta( $post_id );
        # META BOX - Related Books

        # Get the currently linked books for this author
        $linked_content_ids = $this->get_author_content_ids( $post_id );

        # Get the list of books checked by the user
        if ( array_key_exists('content_ids', $data) && is_array( $data['content_ids'] ) ) {
            $chosen_content_ids = $data['content_ids'];
        } else {
            $chosen_content_ids = array();
        }

        # Build a list of books to be linked or unlinked from this author
        $to_remove = array();
        $to_add = array();

        if ( 0 < count( $chosen_content_ids ) ) {
            # The user chose at least one book to link to
            if ( 0 < count( $linked_content_ids ) ) {
                # We already had at least one book linked

                # Cycle through existing and note any that the user did not have checked
                foreach ( $linked_content_ids as $book_id ) {
                    if ( ! in_array( $book_id, $chosen_content_ids ) ) {
                        # Currently linked, but not chosen. Remove it.
                        $to_remove[] = $book_id;
                    }
                }

                # Cycle through checked and note any that are not currently linked
                foreach ( $chosen_content_ids as $book_id ) {
                    if ( ! in_array( $book_id, $linked_content_ids ) ) {
                        # Chosen but not in currently linked. Add it.
                        $to_add[] = $book_id;
                    }
                }

            } else {
                # No previously chosen ids, simply add them all
                $to_add = $chosen_content_ids;
            }

        } else if ( 0 < count( $linked_content_ids ) ) {
            # No properties chosen to be linked. Remove all currently linked.
            $to_remove = $linked_content_ids;
        }

        if ( 0 < count($to_add) ) {
            foreach ( $to_add as $book_id ) {
                # We use add post meta with 4th parameter false to let us link
                # books to as many authors as we want.
                add_post_meta( $book_id, '_credit_id', $post_id, false );
            }
        }

        if ( 0 < count( $to_remove ) ) {
            foreach ( $to_remove as $book_id ) {
                # We specify parameter 3 as we only want to delete the link
                # to this author
                delete_post_meta( $book_id, '_credit_id', $post_id );
            }
        }
    }

    # Books can be linked with multiple authors
    protected function handle_book_meta_changes( $post_id = 0, $data = array() ) {

        # Get the currently linked authors for this book
        $linked_credit_ids = $this->get_book_credit_ids( $post_id );

        # Get the list of authors checked by the user
        if ( array_key_exists('credit_ids', $data) && is_array( $data['credit_ids'] ) ) {
            $chosen_credit_ids = $data['credit_ids'];
        } else {
            $chosen_credit_ids = array();
        }

        # Build a list of authors to be linked or unlinked with this book
        $to_remove = array();
        $to_add = array();

        if ( 0 < count( $chosen_credit_ids ) ) {
            # The user chose at least one author to link to
            if ( 0 < count( $linked_credit_ids ) ) {
                # We already had at least one author already linked

                # Cycle through existing and note any that the user did not have checked
                foreach ( $linked_credit_ids as $author_id ) {
                    if ( ! in_array( $author_id, $chosen_credit_ids ) ) {
                        # Currently linked, but not chosen. Remove it.
                        $to_remove[] = $author_id;
                    }
                }

                # Cycle through checked and note any that are not currently linked
                foreach ( $chosen_credit_ids as $author_id ) {
                    if ( ! in_array( $author_id, $linked_credit_ids ) ) {
                        # Chosen but not in currently linked. Add it.
                        $to_add[] = $author_id;
                    }
                }

            } else {
                # No previously chosen ids, simply add them all
                $to_add = $chosen_credit_ids;
            }

        } else if ( 0 < count( $linked_credit_ids ) ) {
            # No properties chosen to be linked. Remove all currently linked.
            $to_remove = $linked_credit_ids;
        }

        if ( 0 < count($to_add) ) {
            foreach ( $to_add as $author_id ) {
                # We use add post meta with 4th parameter false to let us link
                # to as many authors as we want.
                add_post_meta( $post_id, '_credit_id', $author_id, false );
            }
        }

        if ( 0 < count( $to_remove ) ) {
            foreach ( $to_remove as $author_id ) {
                # We specify parameter 3 as we only want to delete the link
                # to this author
                delete_post_meta( $post_id, '_credit_id', $author_id );
            }
        }
    }

} # end of the class declaration

if ( is_admin() ) {
    new Credits();
}

/**
 * Ajax post scipts for content
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_player_credits() {

	// NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' ); 

	global $wpdb;

	// Get params
	$postId = (int) $_REQUEST['postId'];

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	// GET ALL CREDITS::
    	$credit_ids = get_post_meta( $postId, '_credit_id', false );

    	global $wp_query;
		$area_id = $wp_query->get_queried_object_id();
		$args = array(
		    'post_type' => 'credits',
		    'posts_per_page' => -1,
            'post__in' => $credit_ids
		);
		$properties = new WP_Query( $args );

		if ( $properties->have_posts() ) {

			$buildCredits = [];
		    while( $properties->have_posts() ) {

		        $property = $properties->next_post();
		        array_push($buildCredits, array(
		    		'username' => esc_attr($property->post_title),
		    		'avatar'   => get_the_post_thumbnail_url($property->ID, 'credits_avatar' ),
		    		'post_id'  => $property->ID,
		    		'message'  => esc_attr($property->post_content),
		    		'link'     => get_permalink( $property->ID ),
		    		'time'     => $property->post_date
		    	));

		    }

			wp_send_json(array(
	    		'error' => false,
	    		'title' => 'Credits',
	    		'data' => $buildCredits,
				'message' => __( 'Success', 'streamium' ) 
	    	));

		}else{

			wp_send_json(array(
	    		'error' => true,
	    		'message' => 'We could not find this post.'
	    	));

		}

        die();

    }
    else {
        
        wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
        exit();

    }

}

add_action( 'wp_ajax_nopriv_streamium_get_player_credits', 'streamium_get_player_credits' );
add_action( 'wp_ajax_streamium_get_player_credits', 'streamium_get_player_credits' );


/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function credits_api_post() {

    // NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' ); 

    // Get params
	// GET CURRENT TERM ID::
    if(isset($_REQUEST['query']) && $_REQUEST['query'] != ""){
        if(isset($_REQUEST['query']['ID'])){
            $postId = (int) $_REQUEST['query']['ID'];
        }
    }
    $userId = get_current_user_id();
    $dataPosts = [];

    $args = array(
        'posts_per_page' => (int)get_theme_mod('streamium_global_settings_post_count', 20),
        'post_type' => 'content',
        'meta_query' => array(
            array(
                'key' => '_credit_id',
                'value' => $postId,
                'type' => 'NUMERIC',
                'compare' => '='
            )
        )
    );
    $loop = new WP_Query($args);
    if (is_user_logged_in() && $loop->post_count > 0) {

        // Only run if user is logged in
        if ($loop->have_posts()):
            while ($loop->have_posts()) : $loop->the_post();

                // PLACEHOLDERS IF NOTHING IS SET::
                $tile  = "https://via.placeholder.com/300x169";
                $tileExpanded   = "https://via.placeholder.com/500x281";

                // DEFAULT::
                if (has_post_thumbnail()) : // thumbnail check

                    $tile         =  get_the_post_thumbnail_url(
                        get_the_ID(), 
                        'content_tile_full_width_landscape'
                    );
                    $tileExpanded =  get_the_post_thumbnail_url(
                        get_the_ID(), 
                        'content_tile_full_width_landscape'
                    );

                endif;

                // THESE SHOULD IDEALLY BE SET::
                if (class_exists('MultiPostThumbnails')) {                              
                    
                    // SET ORIENTATION::
                    $thumbs = new MultiPostThumbnails();
                    $type   = get_theme_mod( 'streamium_poster_orientation', 'landscape' );
                    if (MultiPostThumbnails::has_post_thumbnail(get_post_type( get_the_ID() ), 'content_tile_' . $type)) { 
                        $tile         = $thumbs->get_post_thumbnail_url(
                            'content',
                            'content_tile_' . $type, 
                            get_the_ID(), 
                            'content_tile_' . $type
                        );
                        $tileExpanded = $thumbs->get_post_thumbnail_url(
                            'content',
                            'content_tile_expanded_' . $type, 
                            get_the_ID(), 
                            'content_tile_expanded_' . $type
                        );
                    }
                                       
                }

                $paid = false;
                if($loop->post->premium){
                    $paidTileText = str_replace(array("_"), " ", $loop->post->plans[0]);
                    $paid = array(
                        'service' => 'woo',
                        'html'    => '<div class="tile_payment_details"><h2>' .  __( 'Available on', 'streamium' ) . ' <br/>' . $paidTileText . ' ' .  __( 'plan', 'streamium' ) . '</h2></div>',
                    );

                }

                $extraMeta = "";
                $streamium_extra_meta = get_post_meta( get_the_ID(), 'streamium_extra_meta_meta_box_text', true );
                if ( ! empty( $streamium_extra_meta ) ) {
                    $extraMeta = '<h5>' . $streamium_extra_meta . '</h5>';
                }

                // GET COMMENTS::
                $comments_count = wp_count_comments(get_the_ID());

                // GET PERCENTAGE WATCHED::
                $progressBar = streamium_watched_get_stream_percentage(get_the_ID());

                if($progressBar < 95){

                    $dataPosts[] = array(
                        'id'              => get_the_ID(),
                        'post'            => $loop->post,
                        'tileUrl'         => esc_url($tile),
                        'tileUrlExpanded' => esc_url($tileExpanded),
                        'link'            => get_the_permalink(),
                        'title'           => get_the_title(),
                        'text'            => wp_trim_words(get_the_content(), 18, '...'),
                        'paid'            => $paid,
                        'progressBar'     => (int)$progressBar,
                        'extraMeta'       => $extraMeta,
                        'reviews'         => $comments_count->approved,
                        'nonce'           => wp_create_nonce('streamium_likes_nonce')
                    );

                }

            endwhile;
        endif;
        wp_reset_query();

        $profile = streamium_get_current_user_profile();

        $title = ucwords(__('Continue Watching', 'streamium'));
        if(!empty($profile->profile)){
            $title .= ' ' . ucwords($profile->profile);
        }

        wp_send_json(array(
            'error' => false,
            'data' => $dataPosts,
            'meta' => array(
                'count' => (int)$loop->post_count,
                'title' => $title,
                'name' => ucfirst($category->name),
                'home' => esc_url(home_url()),
                'link' => esc_url(home_url()) . '/' . $taxUrl . '/' . $category->slug,
                'orientation' => 'landscape',
                'tileCount'   => 6,
                'catSlug'     => 'recent'
            ),
            'count' => (int)$loop->post_count,
            'message' => 'Success' 
        ));

    }else{

        // user is not logged in
        wp_send_json(array(
            'error' => false,
            'data' => $dataPosts,
            'count' => 0,
            'message' => 'Nothing found' 
        ));

    }       

    die(); 

}

add_action( "wp_ajax_credits_api_post", "credits_api_post" );
add_action( "wp_ajax_nopriv_credits_api_post", "credits_api_post" );