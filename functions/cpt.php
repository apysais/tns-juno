<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'init', 'codex_account_service_init' );
/**
 * Register a book post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function codex_account_service_init() {
  $texdomain = tns_get_text_domain();
	$labels = array(
		'name'               => _x( 'Account Service', 'post type general name', $texdomain ),
		'singular_name'      => _x( 'Account Service', 'post type singular name', $texdomain ),
		'menu_name'          => _x( 'Account Service', 'admin menu', $texdomain ),
		'name_admin_bar'     => _x( 'Account Service', 'add new on admin bar', $texdomain ),
		'add_new'            => _x( 'Add New', 'Account Service', $texdomain ),
		'add_new_item'       => __( 'Add New Account Service', $texdomain ),
		'new_item'           => __( 'New Account Service', $texdomain ),
		'edit_item'          => __( 'Edit Account Service', $texdomain ),
		'view_item'          => __( 'View Account Service', $texdomain ),
		'all_items'          => __( 'All Account Service', $texdomain ),
		'search_items'       => __( 'Search Account Service', $texdomain ),
		'parent_item_colon'  => __( 'Parent Account Service:', $texdomain ),
		'not_found'          => __( 'No Account Service found.', $texdomain ),
		'not_found_in_trash' => __( 'No Account Service found in Trash.', $texdomain )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', $texdomain ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'account-service' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title')
	);

	register_post_type( 'account-service', $args );

  create_account_taxonomies();
  create_services_taxonomies();
  //remove_post_type_support('account-service', 'title');
}

function create_account_taxonomies() {
  $texdomain = tns_get_text_domain();
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Account', 'taxonomy general name', $texdomain ),
		'singular_name'     => _x( 'Account', 'taxonomy singular name', $texdomain ),
		'search_items'      => __( 'Search Account', $texdomain ),
		'all_items'         => __( 'All Account', $texdomain ),
		'parent_item'       => __( 'Parent Account', $texdomain ),
		'parent_item_colon' => __( 'Parent Account:', $texdomain ),
		'edit_item'         => __( 'Edit Account', $texdomain ),
		'update_item'       => __( 'Update Account', $texdomain ),
		'add_new_item'      => __( 'Add New Account', $texdomain ),
		'new_item_name'     => __( 'New Account Name', $texdomain ),
		'menu_name'         => __( 'Account', $texdomain ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => current_user_can( 'update_core' ),
		'show_in_quick_edit' => false,
		'meta_box_cb'       => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'account' ),
	);

	register_taxonomy( 'account', array( 'account-service' ), $args );
}

function create_services_taxonomies() {
  $texdomain = tns_get_text_domain();
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Service', 'taxonomy general name', $texdomain ),
		'singular_name'     => _x( 'Service', 'taxonomy singular name', $texdomain ),
		'search_items'      => __( 'Search Service', $texdomain ),
		'all_items'         => __( 'All Service', $texdomain ),
		'parent_item'       => __( 'Parent Service', $texdomain ),
		'parent_item_colon' => __( 'Parent Service:', $texdomain ),
		'edit_item'         => __( 'Edit Service', $texdomain ),
		'update_item'       => __( 'Update Service', $texdomain ),
		'add_new_item'      => __( 'Add New Service', $texdomain ),
		'new_item_name'     => __( 'New Service Name', $texdomain ),
		'menu_name'         => __( 'Service', $texdomain ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => current_user_can( 'update_core' ),
    'meta_box_cb'       => false,
		'show_in_quick_edit' => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'service' ),
	);

	register_taxonomy( 'service', array( 'account-service' ), $args );
}
