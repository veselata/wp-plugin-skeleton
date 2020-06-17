<?php

if ( ! class_exists( 'Base_Plugin' ) ) {

/**
 * Front controller class
 *
 */
class Base_Plugin extends Base_Module {

const VERSION    = '1.0';
const PREFIX     = 'base_';
const DEBUG_MODE = false;

protected static $readable_properties  = array(); 
protected static $writeable_properties = array();
protected $modules;

/**
 * Constructor
 *
 */
protected function __construct() {
    $this->modules = array(
        'Base_Settings' => Base_Settings::get_instance(),
    );
    
    $this->register_hook_callbacks();
}

/**
 * Initializes variables
 *
 */
public function init() {}

/**
 * Register callbacks for actions and filters
 *
 */
public function register_hook_callbacks() {
    add_action( 'wp_enqueue_scripts',    __CLASS__ . '::load_resources' );
    add_action( 'admin_enqueue_scripts', __CLASS__ . '::load_resources' );
    add_action( 'init',                  array( $this, 'init' ) );
}

/**
 * Enqueues CSS, JavaScript, etc
 *
 */
public static function load_resources() {
    wp_register_script(
        self::PREFIX . 'javascript',
	plugins_url( 'js/javascript.js', dirname( __FILE__ ) ),
	array( 'jquery' ),
	self::VERSION,
	true
    );

    wp_register_style(
        self::PREFIX . 'style',
	plugins_url( 'css/style.css', dirname( __FILE__ ) ),
	array(),
	self::VERSION,
	'all'
    );
}

/**
 * Prepares blogs to use the plugin 
 *
 * @param bool $network_wide
 */
public function activate( $network_wide ) {
    if ( $network_wide && is_multisite() ) {
        $sites = wp_get_sites( array( 'limit' => false ) );

	foreach ( $sites as $site ) {
            switch_to_blog( $site['blog_id'] );
            $this->single_activate( $network_wide );
            restore_current_blog();
	}
    } else {
	$this->single_activate( $network_wide );
    }
}

/**
 * Activation restore
 *
 * @param int $blog_id
 */
public function activate_new_site( $blog_id ) {
    switch_to_blog( $blog_id );
    $this->single_activate( true );
    restore_current_blog();
}

/**
 * Prepares a single blog to use the plugin
 *
 * @param bool $network_wide
 */
protected function single_activate( $network_wide ) {
    foreach ( $this->modules as $module ) {
        $module->activate( $network_wide );
    }
    flush_rewrite_rules();
}

/**
 * Deactivation process
 *
 */
public function deactivate() {
    foreach ( $this->modules as $module ) {
        $module->deactivate();
    }

    flush_rewrite_rules();
}

    }
}
