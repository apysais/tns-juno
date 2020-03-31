<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TNS_Report_SocialManager{
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

  public function get()
  {
		$service_id = TNS_SOCIAL_MANAGER_TERM_ID;
		$args = [
			'services' => $service_id,
			'active' => 'yes'
		];

    $objService = new TNS_Client_JobService;
		$objService->set_wp_query($args);
		$get_query = $objService->get_wp_query();

		//tns_dd($get_query);
		$billing_cycle_arr = [];
		$money_value = 0;

		if($get_query){
			foreach($get_query->posts as $k => $v){
	      $loop_billing_cycle = get_billing_cycle($v->ID);
				//tns_dd($loop_billing_cycle);
				$money_value_fields = get_field('value', $v->ID);
				$data_report['combine_value'] = ($money_value += $money_value_fields);

	      if($loop_billing_cycle >= 12){
	        $loop_billing_cycle = 12;
	      }
	      $billing_cycle_arr[$loop_billing_cycle][] = $v;
	    }

			ksort($billing_cycle_arr);
			//tns_dd($billing_cycle_arr);

			$bracket_arr = [];
			if(!empty($billing_cycle_arr)){
				$total_value = 0;
				$combine_total_value = 0;
				$combine_bonus_value = 0;
				$billing_cycle_total = 0;
				foreach($billing_cycle_arr as $k => $v){
					//if($k <= 5){
					if($k >= 3 && $k <= 5){
						//3-5 months
						$percent = 0.01;
						$billing_cycle = 0;
						foreach($billing_cycle_arr[$k] as $k_one => $v_one){
							$billing_cycle = get_billing_cycle($v_one->ID);
							$money_value_fields = get_field('value', $v_one->ID);
							$term_list = wp_get_post_terms($v_one->ID, 'account', ['fields' => 'names']);
							$start = get_field('start_date', $v_one->ID);
							$carbon_start = tns_carbon()->createFromFormat('d/m/Y', $start);
							$bracket_arr['bracket_1']['data'][] = [
								'id' => $v_one->ID,
								'money_value_fields' => $money_value_fields,
								'account' => $v_one->post_title,
								'client' => $term_list[0],
								'service' => 'Social Media',
								'active' => 'yes',
								'note' => get_field('notes', $v_one->ID),
								'billing_cycle' => get_billing_cycle($v_one->ID),
								'start' => $carbon_start->format('Y/m'),
								'end' => tns_get_end_date($v_one->ID),
								'active' => get_field('active', $v_one->ID)
							];
							$money_total_value = ($total_value += $money_value_fields);
							$billing_cycle_total += $billing_cycle;
						}
						$total_value = array_sum(array_column($bracket_arr['bracket_1']['data'],'money_value_fields'));
						$bracket_arr['bracket_1']['total_value'] = $total_value;
						$bracket_arr['bracket_1']['bonus_value'] = $percent;
						$bracket_arr['bracket_1']['total_bonus_value'] = ($total_value * $percent);
						$bracket_arr['bracket_1']['total_billing'] = count($bracket_arr['bracket_1']['data']);
						$bracket_arr['bracket_1']['total_billing_cycle'] = $billing_cycle_total;
						$combine_total_value = $total_value;
						$combine_bonus_value = $bracket_arr['bracket_1']['total_bonus_value'];
					}elseif($k >= 6 && $k <= 8){
						//6-8 months

						$percent = 0.02;
						$billing_cycle_total = 0;
						foreach($billing_cycle_arr[$k] as $k_two => $v_two){
							$billing_cycle = get_billing_cycle($v_two->ID);
							$money_value_fields = get_field('value', $v_two->ID);
							$term_list = wp_get_post_terms($v_two->ID, 'account', ['fields' => 'names']);
							$start = get_field('start_date', $v_two->ID);
							$carbon_start = tns_carbon()->createFromFormat('d/m/Y', $start);
							$bracket_arr['bracket_2']['data'][] = [
								'id' => $v_two->ID,
								'money_value_fields' => $money_value_fields,
								'account' => $v_two->post_title,
								'client' => $term_list[0],
								'service' => 'Social Media',
								'active' => 'yes',
								'note' => get_field('notes', $v_two->ID),
								'billing_cycle' => $billing_cycle,
								'start' =>  $carbon_start->format('Y/m'),
								'end' => tns_get_end_date($v_two->ID),
								'active' => get_field('active', $v_two->ID)
							];
							$money_total_value = ($total_value += $money_value_fields);
							$billing_cycle_total += $billing_cycle;
						}
						$total_value = array_sum(array_column($bracket_arr['bracket_2']['data'],'money_value_fields'));
						$bracket_arr['bracket_2']['total_value'] = $total_value;
						$bracket_arr['bracket_2']['bonus_value'] = ($percent);
						$bracket_arr['bracket_2']['total_bonus_value'] = ($total_value * $percent);
						$bracket_arr['bracket_2']['total_billing'] = count($bracket_arr['bracket_2']['data']);
						$bracket_arr['bracket_2']['total_billing_cycle'] = $billing_cycle_total;
						$combine_total_value += $total_value;
						$combine_bonus_value += $bracket_arr['bracket_2']['total_bonus_value'];
					}elseif($k >= 9 && $k <= 11){
						//9-11 months
						$percent = 0.03;
						$billing_cycle_total = 0;
						foreach($billing_cycle_arr[$k] as $k_three => $v_three){
							$billing_cycle = get_billing_cycle($v_three->ID);
							$money_value_fields = get_field('value', $v_three->ID);
							$term_list = wp_get_post_terms($v_three->ID, 'account', ['fields' => 'names']);
							$start = get_field('start_date', $v_three->ID);
							$carbon_start = tns_carbon()->createFromFormat('d/m/Y', $start);
							$bracket_arr['bracket_3']['data'][] = [
								'id' => $v_three->ID,
								'money_value_fields' => $money_value_fields,
								'account' => $v_three->post_title,
								'client' => $term_list[0],
								'service' => 'Social Media',
								'active' => 'yes',
								'note' => get_field('notes', $v_three->ID),
								'billing_cycle' => $billing_cycle,
								'start' =>  $carbon_start->format('Y/m'),
								'end' => tns_get_end_date($v_three->ID),
								'active' => get_field('active', $v_three->ID)
							];
							$total_value = ($total_value += $money_value_fields);
							$billing_cycle_total += $billing_cycle;
						}
						$total_value = array_sum(array_column($bracket_arr['bracket_3']['data'],'money_value_fields'));
						$bracket_arr['bracket_3']['total_value'] = $total_value;
						$bracket_arr['bracket_3']['bonus_value'] = ($percent);
						$bracket_arr['bracket_3']['total_bonus_value'] = ($total_value * $percent);
						$bracket_arr['bracket_3']['total_billing'] = count($bracket_arr['bracket_3']['data']);
						$bracket_arr['bracket_3']['total_billing_cycle'] = $billing_cycle_total;
						$combine_total_value += $total_value;
						$combine_bonus_value += $bracket_arr['bracket_3']['total_bonus_value'];
					}elseif($k >= 12){
						//12+ months
						$percent = 0.04;
						$billing_cycle_total = 0;
						foreach($billing_cycle_arr[$k] as $k_four => $v_four){
							$billing_cycle = get_billing_cycle($v_four->ID);
							$money_value_fields = get_field('value', $v_four->ID);
							$term_list = wp_get_post_terms($v_four->ID, 'account', ['fields' => 'names']);
							$start = get_field('start_date', $v_four->ID);
							$carbon_start = tns_carbon()->createFromFormat('d/m/Y', $start);
							$bracket_arr['bracket_4']['data'][] = [
								'id' => $v_four->ID,
								'money_value_fields' => $money_value_fields,
								'account' => $v_four->post_title,
								'client' => $term_list[0],
								'service' => 'Social Media',
								'active' => 'yes',
								'note' => get_field('notes', $v_four->ID),
								'billing_cycle' => $billing_cycle,
								'start' =>  $carbon_start->format('Y/m'),
								'end' => tns_get_end_date($v_four->ID),
								'active' => get_field('active', $v_four->ID)
							];
							$total_value = ($total_value += $money_value_fields);
							$billing_cycle_total += $billing_cycle;
						}
						$total_value = array_sum(array_column($bracket_arr['bracket_4']['data'],'money_value_fields'));
						$combine_total_value += $total_value;
						$bracket_arr['bracket_4']['total_value'] = $total_value;
						$bracket_arr['bracket_4']['bonus_value'] = ($percent);
						$bracket_arr['bracket_4']['total_bonus_value'] = ($total_value * $percent);
						$bracket_arr['bracket_4']['total_billing'] = count($bracket_arr['bracket_4']['data']);
						$bracket_arr['bracket_4']['total_billing_cycle'] = $billing_cycle_total;
						$combine_total_value += $total_value;
						$combine_bonus_value += $bracket_arr['bracket_4']['total_bonus_value'];
					}
				}
			}


			$combine_bonus_value_one = 0;
			if(isset($bracket_arr['bracket_1']['total_bonus_value'])){
				$combine_bonus_value_one = $bracket_arr['bracket_1']['total_bonus_value'];
			}
			$combine_total_value_one = 0;
			if(isset($bracket_arr['bracket_1']['total_value'])){
				$combine_total_value_one = $bracket_arr['bracket_1']['total_value'];
			}

			$combine_bonus_value_two = 0;
			if(isset($bracket_arr['bracket_2']['total_bonus_value'])){
				$combine_bonus_value_two = $bracket_arr['bracket_2']['total_bonus_value'];
			}
			$combine_total_value_two = 0;
			if(isset($bracket_arr['bracket_2']['total_value'])){
				$combine_total_value_two = $bracket_arr['bracket_2']['total_value'];
			}

			$combine_bonus_value_three = 0;
			if(isset($bracket_arr['bracket_3']['total_bonus_value'])){
				$combine_bonus_value_three = $bracket_arr['bracket_3']['total_bonus_value'];
			}
			$combine_total_value_three = 0;
			if(isset($bracket_arr['bracket_3']['total_value'])){
				$combine_total_value_three = $bracket_arr['bracket_3']['total_value'];
			}

			$combine_bonus_value_four = 0;
			if(isset($bracket_arr['bracket_4']['total_bonus_value'])){
				$combine_bonus_value_four = $bracket_arr['bracket_4']['total_bonus_value'];
			}
			$combine_total_value_four = 0;
			if(isset($bracket_arr['bracket_4']['total_value'])){
				$combine_total_value_four = $bracket_arr['bracket_4']['total_value'];
			}

			$bracket_arr['combine_total_value'] = ($combine_total_value_one + $combine_total_value_two + $combine_total_value_three + $combine_total_value_four);
			$bracket_arr['combine_bonus_value'] = ($combine_bonus_value_one + $combine_bonus_value_two + $combine_bonus_value_three + $combine_bonus_value_four);

			return $bracket_arr;
		}
  }

	public function createBracket($arr, $bracket = 0,  $percent = 0)
	{
		$bracket_arr = [];
		$total_value = 0;
		$money_total_value = 0;
		foreach($arr as $k => $v){
			$money_value_fields = get_field('value', $v->ID);
			$term_list = wp_get_post_terms($v->ID, 'account', ['fields' => 'names']);
			$bracket_arr['data'][$bracket][] = [
				'id' => $v->ID,
				'money_value_fields' => $money_value_fields,
				'account' => $term_list[0]
			];
			$money_total_value = ($total_value += $money_value_fields);
		}
		$total_value = array_sum(array_column($bracket_arr[$bracket]['data'],'money_value_fields'));
		$bracket_arr[$bracket]['total_value'] = $total_value;
		$bracket_arr[$bracket]['bonus_value'] = $percent;
		$bracket_arr[$bracket]['total_bonus_value'] = ($total_value * $percent);

		return $bracket_arr;
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

}//TNS_Report_SocialManager
