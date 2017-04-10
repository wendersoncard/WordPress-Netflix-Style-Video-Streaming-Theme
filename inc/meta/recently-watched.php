<?php

/**
 * Function to remove all recently wtached if need use for debug
 *
 * @return bool
 * @author  @sameast
 */
function streamium_remove_all_recently_watched(){

    $allposts = get_posts( 'numberposts=-1&post_type=post&post_status=any' );
    foreach( $allposts as $postinfo ) {
      delete_post_meta( $postinfo->ID, 'recently_watched' );
      delete_post_meta( $postinfo->ID, 'recently_watched_user_id' );
      $inspiration = get_post_meta( $postinfo->ID, 'post_inspiration' );
    }

}