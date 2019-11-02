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
	  $output = '<select id="services" name="services" class="form-control">';
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
	$taxonomies = get_terms( array(
	    'taxonomy' => 'account',
	    'hide_empty' => false
	) );

	if ( !empty($taxonomies) ) :
	  $output = '<select id="client" name="client" class="form-control">';
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
