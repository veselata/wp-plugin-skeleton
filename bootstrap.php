<?php
/*
Plugin Name: WordPress REST Plugin Skeleton Sample
Plugin URI:  https://github.com/veselata/wp-rest-plugin
Description: A plugin skeleton for WordPress REST integration
Version:     1.0
Author:      Veselina Spasova
Author URI:  https://github.com/veselata/wp-rest-plugin
 */


if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

define( 'BASE_NAME', 'Plugin Skeleton' );
define( 'BASE_REQUIRED_PHP_VERSION', '7.3' );   
define( 'BASE_REQUIRED_WP_VERSION',  '5.4' );

/**
 * Checks if the system requirements are met
 *
 * @return bool
 */
function base_requirements_met() {
	$wp_version = get_bloginfo( 'version' );

	if ( version_compare( PHP_VERSION, BASE_REQUIRED_PHP_VERSION, '<' ) ) {
            return false;
	}
	if ( version_compare( $wp_version, BASE_REQUIRED_WP_VERSION, '<' ) ) {
            return false;
	}
	return true;
}

/**
 * Prints an error message.
 */
function base_requirements_error() {
	require_once( dirname( __FILE__ ) . '/views/system-requirements-error.php' );
}

/*
 * Load main classes
 */
if ( base_requirements_met() ) {
    require_once( __DIR__ . '/classes/base-module.php' );
    require_once( __DIR__ . '/classes/base-plugin.php' );
    require_once( __DIR__ . '/classes/base-settings.php' );

    if ( class_exists( 'Base_Plugin' ) ) {
	register_activation_hook(  __FILE__, array( Base_Plugin::get_instance(), 'activate' ) );
	register_deactivation_hook( __FILE__, array( Base_Plugin::get_instance(), 'deactivate' ) );
    }
} else {
    add_action( 'admin_notices', 'base_requirements_error' );
}
