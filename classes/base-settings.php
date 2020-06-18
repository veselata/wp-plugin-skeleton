<?php

if ( ! class_exists( 'Base_Settings' ) ) {

/**
 * Handles plugin settings
 */
class Base_Settings extends Base_Module {
    
const REQUIRED_CAPABILITY = 'administrator';
    
protected $settings;
protected static $default_settings;
protected static $readable_properties  = array( 'settings' );
protected static $writeable_properties = array( 'settings' );

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
    self::$default_settings = self::get_default_settings();
    $this->settings = self::get_settings();
}

/**
 * Register callbacks for actions and filters
 *
 */
public function register_hook_callbacks() {
   add_action( 'admin_menu', __CLASS__ . '::register_settings_pages' );
   add_action( 'init', [$this, 'init']);
   add_action( 'admin_init', [$this, 'register_settings']);
   //add_action( 'admin_post', [$this, 'process_form_data'] );
    
    add_filter(
        'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) ) . '/bootstrap.php',
	__CLASS__ . '::add_plugin_action_links'
    );
    
    if(isset($_POST)){
       // $this->process_form_data();
        //$base_settings = get_option('base_settings');    
        $this->settings = self::validate_settings( $_POST );
        update_option( 'base_settings', $this->settings );
    }
}

/**
 * Update form variables
 *
 * 
 **/
function process_form_data() {  
    if ( ! empty( $_POST['_wp_http_referer'] ) ) {
        $form_url = esc_url_raw( wp_unslash( $_POST['_wp_http_referer'] ) );
    } else {
        $form_url = home_url( '/' );
    }
    if ( isset( $_POST['endpoint'] )
     //   && wp_verify_nonce(
     //       sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ),
     //       'base_settings'
     //   )
        ) {

        $base_settings = get_option('base_settings');    
        $this->settings = self::validate_settings( $base_settings );
        update_option( 'base_settings', $this->settings );

     /*   wp_safe_redirect(
            esc_url_raw(
                add_query_arg( 'form_status', 'success', $form_url )
            )
        );
        exit();*/
    } else {
     /*   wp_safe_redirect(
            esc_url_raw(
                add_query_arg( 'form_status', 'error', $form_url )
            )
        );
       exit(); */
    }
}


public function __set( $variable, $value ) { 
    if ( $variable != 'base_settings' ) {
        return;
    }
    $this->settings = self::validate_settings( $value );
    update_option( 'base_settings', $this->settings );
}

/**
 * Establishes initial values for all settings
 *
 * @return array
 */
protected static function get_default_settings() {
    return array(
        'version' => '1.0',
        'endpoint' => 'http://',
    );
}

/**
 * Retrieves all of the settings from the database
 *
 * @return array
 */
protected static function get_settings() {
    $settings = shortcode_atts(
        self::$default_settings,
	get_option( 'base_settings' )
    );
    return $settings;
}

/**
 * Adds links to the plugin's action link section on the Plugins page
 *
 * @param array $links The links currently mapped to the plugin
 * @return array
 */
public static function add_plugin_action_links( $links ) {
    array_unshift( $links, '<a href="options-general.php?page=' . 'base_settings">Settings</a>' );
    return $links;
}

/**
 * Adds pages to the Admin Panel menu
 *
 */
public static function register_settings_pages() {
    add_options_page(
	BASE_NAME . ' Settings',
	BASE_NAME,
        self::REQUIRED_CAPABILITY,    
	'base_settings',
	__CLASS__ . '::markup_settings_page'
    );
}

/**
 * Creates the markup for the Settings page
 *
 */
public static function markup_settings_page() { 
    if ( current_user_can( self::REQUIRED_CAPABILITY ) ) {
        echo self::render_template( 'base-settings/page-settings.php' );
    } else {
        wp_die( 'Access denied.' );
    }
}

/**
 * Registers settings sections, fields and settings
 *
 */
public function register_settings() {
    /*
     * Basic Section
     */
    add_settings_section(
        'base_section-basic',
        'Basic Settings',
        __CLASS__ . '::markup_section_headers',
        'base_settings'
    );

    add_settings_field(
        'endpoint',
        'Endpoint',
        array( $this, 'markup_endpoint' ),
        'base_settings',
        'base_section-basic',
        array( 'label_for' => 'endpoint' )
    );

    // The settings container
    register_setting(
        'base_settings',
        'base_settings',
        array( $this, 'validate_settings' )
    ); 
}

/**
 * Display endpoint text field
 *
 */
function markup_endpoint() {
	$options = get_option('base_settings');
        var_dump($options);
	echo '<input id="endpoint_text_field" name="endpoint" size="48" type="text" value="'.esc_url($options['endpoint']) .'" />';
}

/**
 * Display introduction text to the Settings page
 *
 *
 * @param array $section
 */
public static function markup_section_headers( $section ) {
    echo self::render_template( 'base-settings/page-settings-headers.php', array( 'section' => $section ), 'always' );
}

/**
 * Validates setting values
 *
 *
 * @param array $new_settings
 * @return array
 */
public function validate_settings( $new_settings ) { 
    $new_settings = shortcode_atts( $this->settings, $new_settings );
    if ( ! is_string( $new_settings['version'] ) ) {
        $new_settings['version'] = Base_Plugin::VERSION;
    }
    if (filter_var($new_settings['endpoint'], FILTER_VALIDATE_URL) == false) {
        //add_notice( 'Parameter Endpoint must be a valid URL', 'error' );
	$new_settings['endpoint'] = self::$default_settings['endpoint'];
    }
 
    return $new_settings;
}

/**
 * Prepares site to use the plugin during activation
 *
 * @param bool $network_wide
 */
public function activate( $network_wide ) {}

/**
 * Deactivation process
 *
 */
public function deactivate() {}

    }
}
