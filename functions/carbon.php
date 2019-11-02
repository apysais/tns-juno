<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

function tns_carbon()
{
  return TNS_Carbon::get_instance()->init();
}
