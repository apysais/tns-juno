<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
* Get the client job services.
**/
class TNS_Client_JobService{
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

		public $wp_query;

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

  public function __construct(){}

  /**
  * get the query via wp_query.
  **/
  public function wp_get($args = [])
  {
    // The Query
    $query_args = [
      'posts_per_page'  => -1,
      'post_type'       => 'account-service',
      'post_status'     => 'publish',
			'meta_query'			=> [],
			'tax_query'				=> [],
    ];
		//meta query
		$search_by = 'year';
		if(isset($args['search_by'])){
			$search_by = $args['search_by'];
		}
		$start_end_date = '';
		if($search_by == 'year'){
			if(
				isset($args['start_date'])
				&& $args['start_date']
				&& $args['end_date'] == ''
			){
				$start_end_date = [
	        'key'     => 'start_date_y',
	        'value'   => date('Y', strtotime($args['start_date'])),
	        'compare' => '='
	      ];
			}elseif(isset($args['start_date']) && $args['start_date'] != '' && $args['end_date'] != ''){
				$_start = date('Y', strtotime($args['start_date']));
				$_end = date('Y', strtotime($args['end_date']));
				$start_end_date = [
	        'key'     => 'start_date_y',
	        'value'   => [$_start, $_end],
	        'type'   => 'numeric',
	        'compare' => 'BETWEEN'
	      ];
			}
			$query_args['meta_query'][] = $start_end_date;
		}else{
			if(
				isset($args['start_date'])
				&& $args['start_date']
				&& $args['end_date'] == ''
			){
				$start_end_date = [
	        'key'     => 'start_date_ym',
	        'value'   => date('Ym', strtotime($args['start_date'])),
	        'compare' => '='
	      ];
			}elseif($args['start_date'] != '' && $args['end_date'] != ''){

				$_start = date('Ym', strtotime($args['start_date']));
				$_end = date('Ym', strtotime($args['end_date']));
				$start_end_date = [
	        'key'     => 'start_date_ym',
	        'value'   => [$_start, $_end],
	        'type'   => 'numeric',
	        'compare' => 'BETWEEN'
	      ];
			}
			$query_args['meta_query'][] = $start_end_date;
		}

		if(isset($args['active']) && $args['active']){
			$active = [
				'key'     => 'active',
	      'value'   => $args['active'],
			];
			$query_args['meta_query'][] = $active;
		}

		//tax query
		if(isset($args['services']) && $args['services']){
			$tax_services = [
				'taxonomy' => 'service',
				'terms'    => $args['services'],
			];
			$query_args['tax_query'][] = $tax_services;
		}

		if(isset($args['client']) && $args['client']){
			$tax_client = [
				'taxonomy' => 'account',
				'terms'    => $args['client'],
			];
			$query_args['tax_query'][] = $tax_client;
		}

		//tns_dd($query_args);
    $the_query = new WP_Query( $query_args );
		//tns_dd($the_query);
    /* Restore original Post Data */
    wp_reset_postdata();

    return $the_query;
  }

  /**
  * set the wp query and re use later.
  **/
  public function set_wp_query($report_arg)
  {
    $this->wp_query = $this->wp_get($report_arg);
  }

  /**
  * get the variable $wp_query.
  **/
  public function get_wp_query()
  {
    return $this->wp_query;
  }

  /**
  * get the data.
  **/
  public function get_data()
  {
    $wp_query = $this->wp_get();
    return $wp_query;
  }

}//TNS_Client_JobService
