<?php

/**
 * The plugin bootstrap file
 *
 *
 *
 * Plugin Name:       Max Bol Api Integration
 * Plugin URI:        https://maxenius.com/
 * Description:       Bol api integration plugin built by Maxenius Solutions.
 * Version:           1.0.3
 * Author:            Maxenius Solutions
 * Author URI:        https://maxenius.com/
 * License:           GPL-2.0
 * Text Domain:       max-bol-api-integration
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! defined( 'WP_AUTO_UPDATE_CORE' ) ) {
    define( 'WP_AUTO_UPDATE_CORE', true );
}

/**
 *plugin version.
 */
define( 'MAX_BOL_VERSION', '1.0.3' );
/**
 * Plugin activator function
 */
function activate_MAX_BOL() {
    
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-max-bol-activator.php';
	MAX_BOL_Activator::activate();

}

/**
 *Plugin deactivator function
 */
function deactivate_MAX_BOL() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-max-bol-deactivator.php';
	MAX_BOL_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_MAX_BOL' );
register_deactivation_hook( __FILE__, 'deactivate_MAX_BOL' );

require plugin_dir_path( __FILE__ ) . 'includes/class-max-bol.php';
/**
 * Start plugin's execution.
 *
 */
function run_MAX_BOL_max() {

    $main = new MAX_BOL();
    $main->max_run();
}
run_MAX_BOL_max();
