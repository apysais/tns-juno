<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
* Get client.
**/
class TNS_Client_Get{
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

  public function active()
  {
    $objService = new TNS_Client_JobService;
    $args = [
      'active' => 'yes'
    ];
    return $this->getClients($args);
  }

  public function notActive()
  {
    $args = [
      'active' => 'no'
    ];
    return $this->getClients($args);
  }

	public function getClients($args = [])
	{
		$objService = new TNS_Client_JobService;
    $objService->set_wp_query($args);
		$get_query = $objService->get_wp_query();

    $clients = [];
    if($get_query){
      foreach($get_query->posts as $k => $v){
        $terms = get_the_terms( $v->ID, 'account');
        if(isset($terms[0]->term_id)){
          $term = $terms[0];
          $clients[$term->term_id] = $term->name;
        }

      }
    }
    return $clients;
	}

}//TNS_Client_Get
