<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              truenorthsocial.com
 * @since             1.0.0
 * @package           Tns_Juno
 *
 * @wordpress-plugin
 * Plugin Name:       TNS Juno
 * Plugin URI:        truenorthsocial.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            True North Social
 * Author URI:        truenorthsocial.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tns-juno
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TNS_JUNO_VERSION', '1.0.0' );
define('CURRENCY_SYMBOL', '$');
require plugin_dir_path( __FILE__ ) . 'library/carbon/autoload.php';

/**
 * For autoloading classes
 * */
spl_autoload_register('tns_directory_autoload_class');
function tns_directory_autoload_class($class_name){
	if ( false !== strpos( $class_name, 'TNS' ) ) {
		$include_classes_dir = realpath( get_template_directory( __FILE__ ) ) . DIRECTORY_SEPARATOR;
		$admin_classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;
		$class_file = str_replace( '_', DIRECTORY_SEPARATOR, $class_name ) . '.php';
		if( file_exists($include_classes_dir . $class_file) ){
			require_once $include_classes_dir . $class_file;
		}
		if( file_exists($admin_classes_dir . $class_file) ){
			require_once $admin_classes_dir . $class_file;
		}
	}
}
function tns_get_plugin_details(){
	// Check if get_plugins() function exists. This is required on the front end of the
	// site, since it is in a file that is normally only loaded in the admin.
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$ret = get_plugins();
	return $ret['tns-juno/tns-juno.php'];
}
function tns_get_text_domain(){
	$ret = tns_get_plugin_details();
	return $ret['TextDomain'];
}
function tns_get_plugin_dir(){
	return plugin_dir_path( __FILE__ );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tns-juno-activator.php
 */
function activate_tns_juno() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tns-juno-activator.php';
	Tns_Juno_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tns-juno-deactivator.php
 */
function deactivate_tns_juno() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tns-juno-deactivator.php';
	Tns_Juno_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tns_juno' );
register_deactivation_hook( __FILE__, 'deactivate_tns_juno' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tns-juno.php';
require plugin_dir_path( __FILE__ ) . 'functions/cpt.php';
require plugin_dir_path( __FILE__ ) . 'functions/columns.php';
require plugin_dir_path( __FILE__ ) . 'functions/helper.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tns_juno() {

	$plugin = new Tns_Juno();
	$plugin->run();

	//TNS_Carbon::get_instance()->getMonthDifference('','');
}
//run_tns_juno();
add_action('plugins_loaded', 'run_tns_juno');
