<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;

class TNS_Carbon{
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

	public function init()
	{
		$carbon = new Carbon;
		return $carbon;
	}

	public function CarbonPeriod()
	{
		$carbon = new CarbonPeriod;
		return $carbon;
	}

	public function getDate($date)
	{
		return Carbon::createFromFormat('d/m/Y', $date);
	}

	public function getMonthListFromDate(Carbon $start)
  {
      foreach (CarbonPeriod::create($start, '1 month', Carbon::today()) as $month) {
          $months[$month->format('m-Y')] = $month->format('F Y');
      }
      return $months;
  }


}
