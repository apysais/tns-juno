<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

function set_title($post_id) {
    // If this is a revision, get real post ID
    if ( $parent_id = wp_is_post_revision( $post_id ) )
        $post_id = $parent_id;

    if(isset($_POST['post_type']) && $_POST['post_type'] == 'account-service'){
      $account_service_title = '';

      $account_name = '';
      if( isset($_POST['acf']['field_5dae96ddb5a33']) ){
        $account_term_id = $_POST['acf']['field_5dae96ddb5a33'];
        $acf_account_id = get_term_by('id', $account_term_id, 'account');
        if($acf_account_id){
          $account_name = $acf_account_id->name;
        }
				wp_set_post_terms( $post_id, $account_term_id, 'account' );
      }

      $service_name = '';
      if( isset($_POST['acf']['field_5dae97d5b5a38']) ){
        $service_term_id = $_POST['acf']['field_5dae97d5b5a38'];
        $acf_service_id = get_term_by('id', $service_term_id, 'service');
        if($acf_service_id){
          $service_name = $acf_service_id->name;
        }
				wp_set_post_terms( $post_id, $service_term_id, 'service' );
      }
      $account_service_title = $account_name .' - '. $service_name;

			$start_date_ym = '';
			if(isset($_POST['acf']['field_5dae9746b5a35'])){
				$start_date_ym = date('Ym', strtotime($_POST['acf']['field_5dae9746b5a35']));
				update_post_meta($post_id, 'start_date_ym', $start_date_ym);
			}

			$end_date_ym = '';
			if(isset($_POST['acf']['field_5dae9777b5a36'])){
				$end_date_ym = date('Ym', strtotime($_POST['acf']['field_5dae9777b5a36']));
				update_post_meta($post_id, 'end_date_ym', $end_date_ym);
			}

			if(isset($_POST['acf']['field_5dae971cb5a34']) && $_POST['acf']['field_5dae971cb5a34'] == 'yes'){
				delete_post_meta($post_id, 'end_date_ym');
			}
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

function get_billing_cycle($post_id)
{
	$billing_cycle_adjustment = get_field('billing_cycle_adjustment', $post_id);

	$start = get_field('start_date', $post_id);
	$carbon_start_date = TNS_Carbon::get_instance()->init()->createFromFormat('d/m/Y', $start);

	$end = get_field('end_date', $post_id);
	$active = get_field('active', $post_id);

	$billing_cycle = 0;

	if(!$end){
		if($active == 'yes'){
			$now = TNS_Carbon::get_instance()->init()->now();
			if($carbon_start_date->isFuture()){
				$end = $carbon_start_date->format('d/m/Y');
			}else{
				$end = $now->format('d/m/Y');
			}
		}
	}

	$end = TNS_Carbon::get_instance()->init()->createFromFormat('d/m/Y', $end);
	$start = TNS_Carbon::get_instance()->init()->createFromFormat('d/m/Y', $start);

	$period = TNS_Carbon::get_instance()->CarbonPeriod()->create($start, $end);

	$months = [];
	foreach($period as $month){
		$months[$month->format('m-Y')] = $month->format('F Y');
	}

	$billing_cycle = count($months);

	if ($billing_cycle_adjustment < 0)
	{
		 $n_number = preg_replace('/\D/', '', $billing_cycle_adjustment);
		 $billing_cycle = ($billing_cycle - $n_number);
	}else{
		if($billing_cycle_adjustment != 0 ){
			$billing_cycle = ($billing_cycle + $billing_cycle_adjustment);
		}
	}

	return $billing_cycle;
}

function tns_money_format($money_value, $decimal = 2)
{
	return CURRENCY_SYMBOL . number_format($money_value, $decimal);
}

function tns_dd($arr)
{
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

function get_meta_sql_date( $pieces, $queries ) {
    global $wpdb;
		$start_date = '';
		$end_date = '';
    // get start and end date from query
    foreach ( $queries as $q ) {

        if ( !isset( $q['key'] ) ) {
            return $pieces;
        }

        if ( 'start_date_ym' === $q['key'] ) {
            $start_date = isset( $q['value'] ) ?  $q['value'] : '';
        }
        if ( 'end_date_ym' === $q['key'] ) {
            $end_date = isset( $q['value'] ) ?  $q['value'] : '';
        }
    }

    if ( ( '' === $start_date ) || ( '' === $end_date ) ) {
        return $pieces;
    }

    $query = "";

    // after start date AND before end date
    $_query = " AND (
        ( $wpdb->postmeta.meta_key = 'start_date_ym' AND ( CAST($wpdb->postmeta.meta_value AS DATE) >= %s) )
        AND ( mt1.meta_key = 'end_date_ym' AND ( CAST(mt1.meta_value AS DATE) <= %s) )
    )";
    $query .= $wpdb->prepare( $_query, $start_date, $end_date );

    // OR before start date AND after end end date
    $_query = " OR (
        ( $wpdb->postmeta.meta_key = 'start_date_ym' AND ( CAST($wpdb->postmeta.meta_value AS DATE) <= %s) )
        AND ( mt1.meta_key = 'end_date_ym' AND ( CAST(mt1.meta_value AS DATE) >= %s) )
    )";
    $query .= $wpdb->prepare( $_query, $start_date, $end_date );

    // OR before start date AND (before end date AND end date after start date)
    $_query = " OR (
        ( $wpdb->postmeta.meta_key = 'start_date_ym' AND ( CAST($wpdb->postmeta.meta_value AS DATE) <= %s) )
        AND ( mt1.meta_key = 'end_date_ym'
            AND ( CAST(mt1.meta_value AS DATE) <= %s )
            AND ( CAST(mt1.meta_value AS DATE) >= %s )
        )
    )";
    $query .= $wpdb->prepare( $_query, $start_date, $end_date, $start_date );

    // OR after end date AND (after start date AND start date before end date) )
    $_query = "OR (
        ( mt1.meta_key = 'end_date_ym' AND ( CAST(mt1.meta_value AS DATE) >= %s ) )
        AND ( $wpdb->postmeta.meta_key = 'start_date_ym'
            AND ( CAST($wpdb->postmeta.meta_value AS DATE) >= %s )
            AND ( CAST($wpdb->postmeta.meta_value AS DATE) <= %s )
        )
    )";
    $query .= $wpdb->prepare( $_query, $end_date, $start_date, $end_date );

    $pieces['where'] = $query;

    return $pieces;
}

/**
*
**/
function tns_get_end_date($post_id)
{
	$start_date = get_field('start_date', $post_id);
	$carbon_start_date = TNS_Carbon::get_instance()->init()->createFromFormat('d/m/Y', $start_date);

	$end_date =  get_field('end_date', $post_id);
	$str_end_date = '';
	if($end_date){
		$date = TNS_Carbon::get_instance()->getDate($end_date);
		$str_end_date = $date->format('Y/m');
	}else{
		$active = get_field('active', $post_id);
		if($active == 'yes'){
			if(!$carbon_start_date->isFuture()){
				$carbon_start_date = TNS_Carbon::get_instance()->init()->now();
			}
			$end = $carbon_start_date;
			$str_end_date = $end->format('Y/m');
		}
	}
	return $str_end_date;
}
