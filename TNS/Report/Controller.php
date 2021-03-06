<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Controller for the report page.
 * @since 0.0.1
 * */
class TNS_Report_Controller extends TNS_Base {
  /**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
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

	public function report_retention()
	{
    $data = [];
		$services = '';
		if(isset($_POST['services']) && $_POST['services']){
			$services = $_POST['services'];
		}

		$client = '';
		if(isset($_POST['client']) && $_POST['client']){
			$client = $_POST['client'];
		}

		$active = '';
		if(isset($_POST['active']) && $_POST['active']){
			$active = $_POST['active'];
		}

		$meta_start_date = '';
		if(isset($_POST['startDate']) && $_POST['startDate']){
			$start_date = $_POST['startDate'];
			$carbon_start_date = tns_carbon()->createFromFormat('d/m/Y', $start_date);
			$meta_start_date = $carbon_start_date->format('Y/m/d');
		}

		$meta_end_date = '';
		if(isset($_POST['endDate']) && $_POST['endDate']){
			$end_date = $_POST['endDate'];
			$carbon_end_date = tns_carbon()->createFromFormat('d/m/Y', $end_date);
			//$meta_end_date = $carbon_end_date->year . $carbon_end_date->month . $carbon_end_date->day;
			$meta_end_date = $carbon_end_date->format('Y/m/d');
		}

		$search_by = '';
		if(isset($_POST['search-by'])){
			$search_by = $_POST['search-by'];
		}

		$report_arg = [
			'start_date' => $meta_start_date,
			'end_date' => $meta_end_date,
			'services' => $services,
			'client' => $client,
			'active' => $active,
			'search_by' => $search_by,
		];

		$show_collapse = false;

		$ret = tns_get_retention($report_arg);
    $data['retention'] = $ret;
		$data['post_request'] = $report_arg;

		$data['post_start_date'] = '';
		if( isset($start_date) && $start_date){
			$data['post_start_date'] = $start_date;
			$show_collapse = true;
		}
		$data['post_end_date'] = '';
		if( isset($end_date) && $end_date){
			$data['post_end_date'] = $end_date;
			$show_collapse = true;
		}
		$data['post_service'] = '';
		if( isset($services) && $services){
			$data['post_service'] = $services;
			$show_collapse = true;
		}
		$data['post_client'] = '';
		if( isset($client) && $client){
			$data['post_client'] = $client;
			$show_collapse = true;
		}
		$data['post_active'] = '';
		if( isset($active) && $active){
			$data['post_active'] = $active;
			$show_collapse = true;
		}
		$data['post_search_by'] = 'full';
		if( isset($search_by) && $search_by){
			$data['post_search_by'] = $search_by;
			$show_collapse = true;
		}
		$data['show_collapse'] = $show_collapse;
		TNS_View::get_instance()->admin_partials('report/retention/index.php', $data);
	}

	public function social_manager()
	{
		$data = [];
		$ret = TNS_Report_SocialManager::get_instance()->get();
		$data['bracket'] = $ret;
		TNS_View::get_instance()->admin_partials('report/social/social.php', $data);
	}

	/**
	 * Controller
	 *
	 * @param	$action		string | empty
	 * @parem	$arg		array
	 * 						optional, pass data for controller
	 * @return mix
	 * */
	public function controller($action = '', $arg = array()){
		$this->call_method($this, $action);
	}

	public function __construct(){}

}//TNS_Report_Controller
