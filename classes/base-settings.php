<?php

if ( ! class_exists( 'Base_Settings' ) ) {

/**
 * Handles plugin settings
 */
class Base_Settings extends Base_Module {
    
const REQUIRED_CAPABILITY = 'administrator';
    
protected $settings = [
    'endpoint' => 'http://',
];
protected static $readable_properties  = ['settings'];
protected static $writeable_properties = ['settings'];

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
    if(filter_input_array(INPUT_POST)){
       $this->process_form_data();     
    }
}

/**
 * Register callbacks for actions and filters
 *
 */
public function register_hook_callbacks() {
    add_action( 'admin_menu', __CLASS__ . '::register_settings_pages' );
    add_action( 'init', [$this, 'init']);
    add_action( 'admin_init', [$this, 'register_settings']);
    
    add_filter(
        'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) ) . '/bootstrap.php',
	__CLASS__ . '::add_plugin_action_links'
    );
}

/**
 * Update form variables
 *
 * 
 **/
function process_form_data() {  
    $postData = filter_input_array(INPUT_POST);
    
    if ( ! empty( $postData['_wp_http_referer'])) {
        $form_url = esc_url_raw( wp_unslash( $postData['_wp_http_referer'] ) );
    } else {
        $form_url = home_url( '/' );
    }
    if ( isset( $postData['endpoint'])) {
     
        if(update_option( 'base_settings', ['endpoint' => $postData['endpoint']] )) {
            echo "<div class='updated'><p>Your changes have been saved successfully</p></div>";
            $this->settings = get_option( 'base_settings');
        }
    } 
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
    register_setting(
        'base_settings',
        'base_settings',
        [$this, 'sanitize_settings']
    ); 
    
    add_settings_section(
        'base_section-basic',
        'Basic Settings',
        __CLASS__ . '::markup_section_headers',
        'base_settings'
    );

    add_settings_field(
        'endpoint',
        'Endpoint',
        [$this, 'endpoint_callback'],
        'base_settings',
        'base_section-basic',
        ['label_for' => 'endpoint']
    );
}

/**
 * Display endpoint text field
 *
 */
function endpoint_callback() {
    $endpoint = (get_option('base_settings') ? get_option('base_settings')['endpoint'] : $this->settings['endpoint']);
    echo '<input id="endpoint_text_field" name="endpoint" size="48" type="text" value="'.esc_url($endpoint) .'" />';
}

/**
 * Display introduction text to the Settings page
 *
 *
 * @param array $section
 */
public static function markup_section_headers( $section ) {
    echo self::render_template( 'base-settings/page-settings-headers.php', ['section' => $section], 'always' );
}

/**
 * Validates setting values
 *
 *
 * @param array $new_settings
 * @return array
 */
public function sanitize_settings( $settings ) { 
    $new_settings = shortcode_atts( $this->settings, $settings );

    if (!filter_var($new_settings['endpoint'], FILTER_VALIDATE_URL)) {
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
