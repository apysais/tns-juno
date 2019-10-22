<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

function set_title($post_id) {
    // If this is a revision, get real post ID
    if ( $parent_id = wp_is_post_revision( $post_id ) )
        $post_id = $parent_id;

    if($_POST['post_type'] == 'account-service'){

      $account_service_title = '';

      $account_name = '';
      if( isset($_POST['acf']['field_5dae96ddb5a33']) ){
        $account_term_id = $_POST['acf']['field_5dae96ddb5a33'];
        $acf_account_id = get_term_by('id', $account_term_id, 'account');
        if($acf_account_id){
          $account_name = $acf_account_id->name;
        }
      }

      $service_name = '';
      if( isset($_POST['acf']['field_5dae97d5b5a38']) ){
        $service_term_id = $_POST['acf']['field_5dae97d5b5a38'];
        $acf_service_id = get_term_by('id', $service_term_id, 'service');
        if($acf_service_id){
          $service_name = $acf_service_id->name;
        }
      }
      $account_service_title = $account_name .' - '. $service_name;

      // unhook this function so it doesn't loop infinitely
      remove_action( 'save_post', 'set_title' );

      $post_title = $_POST['post_title'];
      if($post_title == ''){
        $post_title = $account_service_title;
      }

      // update the post, which calls save_post again
      wp_update_post( array( 'ID' => $post_id, 'post_title' => $post_title ) );

      // re-hook this function
      add_action( 'save_post', 'set_title' );
    }

}
add_action( 'save_post', 'set_title' );

// hide from Contributors
function tns_remove_this_menu() {
    if ( current_user_can( 'editor' ) || current_user_can( 'author' ) || current_user_can( 'contributor' ) ) :
        remove_menu_page( 'edit.php?post_type=page' ); // Page
        remove_menu_page( 'upload.php' ); // Media
        remove_menu_page( 'edit.php' ); // Posts
        remove_menu_page( 'tools.php' ); // Tools
        remove_menu_page( 'edit-comments.php' ); // Comments
    endif;
}
add_action( 'admin_menu', 'tns_remove_this_menu' );
