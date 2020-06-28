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
define( 'BASE_PLUGIN_DIR', plugin_dir_path(  __FILE__  ) );


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
    require_once( __DIR__ . '/classes/base-shortcode.php' );
    require_once( __DIR__ . '/classes/base-widget.php' );

    if ( class_exists( 'Base_Plugin' ) ) {
	register_activation_hook(  __FILE__, [Base_Plugin::get_instance(), 'activate'] );
	register_deactivation_hook( __FILE__, [Base_Plugin::get_instance(), 'deactivate'] );
        
        add_action('wp_enqueue_scripts', [Base_Plugin::get_instance(), 'init']);
    }
    if(class_exists('Base_Shortcode')) {
        add_shortcode( 'display-base', ['Base_Shortcode', 'display_base_shortcode'] );
        add_action( 'widgets_init', ['Base_Shortcode', 'init'] );
    }
    
} else {
    add_action( 'admin_notices', 'base_requirements_error' );
}
