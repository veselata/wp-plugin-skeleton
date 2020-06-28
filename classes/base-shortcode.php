<?php

if ( ! class_exists( 'Base_Shortcode' ) ) {

/**
 * Handles shortcode settings
 */
class Base_Shortcode extends Base_Plugin {
    
 /**
 * Constructor
 *
 */
protected function __construct() {
    $this->register_hook_callbacks();
}

/**
 * Initializes variables
 *
 */
public function init() {
    register_widget( 'Base_Widget' );
}

/**
 * Register callbacks for actions and filters
 *
 */
public function register_hook_callbacks() {
    parent::register_hook_callbacks();
}

/**
 * Defines the [display-base] shortcode
 *
 * @param array $attributes
 * return string
 */
public static function display_base_shortcode( $attributes = []) {
    return self::render_template( 'base-display/display-shortcode.php', ['attributes' => $attributes]);
}

}
}