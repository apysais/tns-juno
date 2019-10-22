<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Add the custom columns to the book post type:
add_filter( 'manage_account-service_posts_columns', 'set_custom_edit_account_service_columns' );
function set_custom_edit_account_service_columns($columns) {

    unset($columns['taxonomy-account']);
    unset($columns['taxonomy-service']);
    unset($columns['date']);
    $columns['start_date'] = __( 'Start', 'your_text_domain' );
    $columns['end_date'] = __( 'End', 'your_text_domain' );
    $columns['billing-cycles'] = __( 'Billing Cycle', 'your_text_domain' );
    $columns['account'] = __( 'Account', 'your_text_domain' );
    $columns['service'] = __( 'Service', 'your_text_domain' );
    $columns['value'] = __( 'Value', 'your_text_domain' );
    $columns['active'] = __( 'Active', 'your_text_domain' );
    $columns['notes'] = __( 'Notes', 'your_text_domain' );
    $columns['date'] = __( 'Date', 'your_text_domain' );

    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_account-service_posts_custom_column' , 'custom_account_service_column', 10, 2 );
function custom_account_service_column( $column, $post_id ) {
    switch ( $column ) {
        case 'start_date' :
            echo get_field($column, $post_id);
            break;
        case 'end_date' :
            echo get_field($column, $post_id);
            break;
        case 'billing-cycles' :
            echo 'Billing Cycle';
            break;
        case 'account' :
            echo 'Account';
            break;
        case 'service' :
            echo 'Service';
            break;
        case 'value' :
            echo get_field($column, $post_id);
            break;
        case 'active' :
            echo get_field($column, $post_id);
            break;
        case 'notes' :
            echo get_field($column, $post_id);
            break;
        case 'date' :
            break;
    }
}
