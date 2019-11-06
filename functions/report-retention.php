<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

function tns_get_retention($report_arg)
{
  return TNS_Report_Retention::get_instance()->getHasMonth($report_arg);
}

function tns_get_service_dropdown($sel_value = '')
{
	$taxonomies = get_terms( array(
	    'taxonomy' => 'service',
	    'hide_empty' => false
	) );

	if ( !empty($taxonomies) ) :
	  $output = '<select id="services" name="services" class="form-control form-control-sm">';
		$output.= '<option value="0">Select</option>';
	  foreach( $taxonomies as $category ) {
			$selected = '';
			if($sel_value == $category->term_id){
				$selected = 'selected';
			}
			$output.= '<option value="'. esc_attr( $category->term_id ) .'" '.$selected.'>
					'. esc_html( $category->name ) .'</option>';
	  }
	  $output.='</select>';
	  echo $output;
	endif;
}

function tns_get_all_client_dropdown($sel_value = '')
{
	$taxonomies = get_terms( array(
	    'taxonomy' => 'account',
	    'hide_empty' => false
	) );

	if ( !empty($taxonomies) ) :
	  $output = '<select id="client" name="client" class="form-control form-control-sm">';
		$output.= '<option value="0">Select</option>';
	  foreach( $taxonomies as $category ) {
			$selected = '';
			if($sel_value == $category->term_id){
				$selected = 'selected';
			}
			$output.= '<option value="'. esc_attr( $category->term_id ) .'" '.$selected.'>
					'. esc_html( $category->name ) .'</option>';
	  }
	  $output.='</select>';
	  echo $output;
	endif;
}

function tns_get_client_dropdown($sel_value = '')
{
	$active = TNS_Client_Get::get_instance()->active();
	$not_active = TNS_Client_Get::get_instance()->notActive();


	$output = '<select id="client" name="client" class="form-control form-control-sm">';
		$output.= '<option value="0">Select</option>';
		if ( !empty($active) ){
			$output.= '<optgroup label="Active Clients">';
			foreach( $active as $k => $v ) {
				$selected = '';
				if($sel_value == $k){
					$selected = 'selected';
				}
				$output.= '<option value="'. esc_attr( $k ) .'" '.$selected.'>
				'. esc_html( $v ) .'</option>';
			}
			$output.='</optgroup>';
		}
		if ( !empty($not_active) ){
			$output.= '<optgroup label="Not Active Clients">';
			foreach( $not_active as $k => $v ) {
				$selected = '';
				if($sel_value == $k){
					$selected = 'selected';
				}
				$output.= '<option value="'. esc_attr( $k ) .'" '.$selected.'>
				'. esc_html( $v ) .'</option>';
			}
			$output.='</optgroup>';
		}
	$output.='</select>';
	echo $output;

}

function tns_get_active_client_dropdown($sel_value = '')
{
	$active = TNS_Client_Get::get_instance()->active();

	$output = '<select id="client" name="client" class="form-control form-control-sm">';
		$output.= '<option value="0">Select</option>';
		if ( !empty($active) ){
			foreach( $active as $k => $v ) {
				$selected = '';
				if($sel_value == $k){
					$selected = 'selected';
				}
				$output.= '<option value="'. esc_attr( $k ) .'" '.$selected.'>
				'. esc_html( $v ) .'</option>';
			}
		}
	$output.='</select>';
	echo $output;

}
