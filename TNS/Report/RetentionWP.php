<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TNS_Report_RetentionWP{
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

  public function addSubMenu()
  {
    $text_domain = tns_get_text_domain();
    add_submenu_page(
        'edit.php?post_type=account-service',
        __( 'Report Retention', $text_domain ),
        __( 'Report Retention', $text_domain ),
        'manage_options',
        'report-retention',
        [TNS_Report_Controller::get_instance(), 'controller']
    );
  }

}//TNS_Report_Retention
