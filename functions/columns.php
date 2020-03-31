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
            $start_date = get_field($column, $post_id);
						$date = TNS_Carbon::get_instance()->getDate($start_date);
						echo $date->year . '/' . $date->month;
            break;
        case 'end_date' :
						echo tns_get_end_date($post_id);
            break;
        case 'billing-cycles' :
						echo  get_billing_cycle($post_id);
            break;
        case 'account' :
						$account = get_field( $column, $post_id );
            echo $account->name;
            break;
        case 'service' :
						$service = get_field( $column, $post_id );
						echo $service->name;
            break;
        case 'value' :
            $money_value = get_field($column, $post_id);
						echo tns_money_format($money_value);
            break;
        case 'active' :
            echo get_field($column, $post_id);
            break;
        case 'notes' :
            echo wp_trim_words(get_field($column, $post_id), 10);
            break;
        case 'date' :
            break;
    }
}

add_filter( 'manage_edit-account-service_sortable_columns', 'account_service_sortable_column' );
function account_service_sortable_column( $columns ) {
    $columns['start_date'] = 'start_date';
    $columns['end_date'] = 'end_date';
    $columns['value'] = 'value';
    $columns['active'] = 'active';
    $columns['billing-cycles'] = 'billing_cycle';

    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);

    return $columns;
}

add_action( 'pre_get_posts', 'account_service_orderby' );
function account_service_orderby( $query ) {
    if( ! is_admin() )
        return;

    $orderby = $query->get( 'orderby');

    if( 'start_date' == $orderby ) {
        $query->set('meta_key','start_date');
        $query->set('orderby','meta_value_num');
    }
    if( 'end_date' == $orderby ) {
        $query->set('meta_key','end_date');
        $query->set('orderby','meta_value_num');
    }
    if( 'active' === $orderby ) {
        $query->set('meta_key','active');
        $query->set('orderby','meta_value');
    }
    if( 'billing_cycle' == $orderby ) {
        $query->set('meta_key','billing_cycle');
        $query->set('orderby','meta_value_num');
    }
    if( 'value' == $orderby ) {
        $query->set('meta_key','value');
        $query->set('orderby','meta_value_num');
    }
}

// add filtering option
function wpc_add_taxonomy_filters() {
	global $typenow;

	// an array of all the taxonomies you want to display. Use the taxonomy name or slug - each item gets its own select box.
	$taxonomies = array( 'account', 'service');

	// use the custom post type here
	if( $typenow == 'account-service' ){
		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms( array(
				'taxonomy' => $tax_slug,
				'hide_empty' => false,
			) );
			if(count($terms) > 0) {
				 echo '<select name="'.$tax_slug.'" id="'.$tax_slug.'" class="postform">';
				 echo '<option value="">Show All '.$tax_name.'</option>';
				 foreach ($terms as $term) {
						 $selected = (isset($_GET[$tax_slug]) == $term->slug) ? 'selected':'';
						 echo '<option value="'.$term->slug.'" '.$selected.'>'.$term->name.'</option>';
				 }
				 echo '</select>';
			}
		}
	}
}
add_action( 'restrict_manage_posts', 'wpc_add_taxonomy_filters' );
