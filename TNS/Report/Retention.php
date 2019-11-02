<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TNS_Report_Retention{
	/**
	 * instance of this class
	 *
	 * @since 3.12
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

    /**
     * use for magic setters and getter
     * we can use this when we instantiate the class
     * it holds the variable from __set
     *
     * @see function __get, function __set
     * @access protected
     * @var array
     * */
    protected $vars = array();

		public $carbon;

    /**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

  public function __construct(){

	}

  public function get_wp_data($wp_query_data)
  {
    $parse_data = [];

    if($wp_query_data->posts)
    {
      $parse_data = $wp_query_data->posts;
    }

    return $parse_data;
  }

  public function get()
  {
		$data_report = [];
    $total_clients = 0;
    $month_in_year = 12;

    $get_job_services = new TNS_Client_JobService;
  	$get_job_services->set_wp_query();
  	$get_query = $get_job_services->get_wp_query();

    $get_total_clients  = $get_query->found_posts;
    $total_clients      = $get_total_clients;

    $data = $this->get_wp_data($get_query);
		$data_report['total_clients'] = $total_clients;

    $billing_cycle_arr = [];
		$money_value = 0;
    foreach($data as $k => $v){
      $loop_billing_cycle = get_billing_cycle($v->ID);

			$money_value_fields = get_field('value', $v->ID);
			$data_report['combine_value'] = ($money_value += $money_value_fields);

      if($loop_billing_cycle >= 12){
        $loop_billing_cycle = 12;
      }
      $billing_cycle_arr[$loop_billing_cycle][] = $v;
    }
    //loop each month in one year.
    $loop_percentage = 0;
		$percentage = 0;
		$keep_percentage_value = 0;
    for($i = 1; $i <= $month_in_year; $i++){

			$calc_loop_percentage = 0;
      $billing_cycle_month = isset($billing_cycle_arr[$i]) ? count($billing_cycle_arr[$i]) : 0;
      $percentage = round( ($billing_cycle_month / $total_clients), 3);

			if($i == 1){
				$calc_loop_percentage = 100;
				$loop_percentage = 0;
      }else{
				$calc_loop_percentage = (100 * (1 - $keep_percentage_value));
      }
			$data_report['data'][$i] = [
				'length_of_time' => $i,
				'completion' => $billing_cycle_month,
				'segment' => $percentage,
				'combine' => $calc_loop_percentage,
			];
      //echo '[length of time]'.$i.' Month completion ['.$billing_cycle_month.'] segment ['.$percentage.'] combine ['.$calc_loop_percentage.']<br>';

			$keep_percentage_value += $percentage;
    }
		return $data_report;
  }

	public function getHasMonth($report_arg)
	{
		$data_report = [];
    $total_clients = 0;
    $month_in_year = 12;

    $get_job_services = new TNS_Client_JobService;

  	$get_job_services->set_wp_query($report_arg);
  	$get_query = $get_job_services->get_wp_query();

		if($get_query){
			$get_total_clients  = $get_query->found_posts;
	    $total_clients      = $get_total_clients;

	    $data = $this->get_wp_data($get_query);
			$data_report['total_clients'] = $total_clients;

	    $billing_cycle_arr = [];
			$money_value = 0;
	    foreach($data as $k => $v){
	      $loop_billing_cycle = get_billing_cycle($v->ID);

				$money_value_fields = get_field('value', $v->ID);
				$data_report['combine_value'] = ($money_value += $money_value_fields);

	      $billing_cycle_arr[$loop_billing_cycle][] = $v;
	    }

			ksort($billing_cycle_arr);

			$loop_percentage = 0;
			$percentage = 0;
			$keep_percentage_value = 0;
			$month = array_keys($billing_cycle_arr);
			$data_report['month'] = $month;

			foreach($month as $k => $v)
			{
				$calc_loop_percentage = 0;
	      $billing_cycle_month = isset($billing_cycle_arr[$v]) ? count($billing_cycle_arr[$v]) : 0;
	      $percentage = round( ($billing_cycle_month / $total_clients), 3);

				if($v == 1){
					$calc_loop_percentage = 100;
					$loop_percentage = 0;
	      }else{
					$calc_loop_percentage = (100 * (1 - $keep_percentage_value));
	      }

				$length_of_time_label = '';
				if($v > 1){
					$length_of_time_label = $v . ' ' . 'Months';
				}else{
					$length_of_time_label = $v . ' ' . 'Month';
				}
				$data_report['data'][$v] = [
					'length_of_time' 				=> $v,
					'length_of_time_label' 	=> $length_of_time_label,
					'completion' 						=> $billing_cycle_month,
					'segment' 							=> $percentage,
					'combine' 							=> $calc_loop_percentage,
				];
	      //echo '[length of time]'.$i.' Month completion ['.$billing_cycle_month.'] segment ['.$percentage.'] combine ['.$calc_loop_percentage.']<br>';

				$keep_percentage_value += $percentage;
			}
		}


		return $data_report;
	}

}//TNS_Report_Retention
