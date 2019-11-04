<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

function tns_get_job_services()
{

}

function tns_is_service_term_by_id($service_term_id = 0)
{
	$terms = [8, 2];
	return in_array($service_term_id, $terms);
}
