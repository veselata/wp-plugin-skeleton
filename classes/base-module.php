<?php
if ( ! class_exists( 'Base_Module' ) ) {

/**
 * Abstract class to define/implement base methods for all module classes
 */
abstract class Base_Module {
    
private static $instances = array();

/**
 * Constructor
 */
abstract protected function __construct();

/**
 * Initializes variables
 */
abstract public function init();

/**
 * Register callbacks for actions and filters
 */
abstract public function register_hook_callbacks();

/**
 * Instance of a module using the Singleton pattern
 *
 * @return object
 */
public static function get_instance() {
    $module = get_called_class();
    if ( ! isset( self::$instances[ $module ] ) ) {
        self::$instances[ $module ] = new $module();
    }
    return self::$instances[ $module ];
}

/**
 * Getter for variables
 *
 * @param string $variable
 * @return mixed
**/
public function __get( $variable ) {
    $module = get_called_class();
    if ( in_array( $variable, $module::$readable_properties ) ) {
        return $this->$variable;
    } else {
        throw new Exception( __METHOD__ . " error: $" . $variable . " doesn't exist or isn't readable." );
    }
}

/**
 * Setter for variable
 *
 * @param string $variable
 * @param mixed  $value
 */
public function __set( $variable, $value ) {
    $module = get_called_class();
    if ( in_array( $variable, $module::$writeable_properties ) ) {
        $this->$variable = $value;
        if ( ! $this->is_valid() ) {
            throw new Exception( __METHOD__ . ' error: $' . $value . ' is not valid.' );
        }
    } else {
        throw new Exception( __METHOD__ . " error: $" . $variable . " doesn't exist or isn't writable." );
    }
}

/**
 * Template render 
 *
 *
 * @param  string $default_template_path
 * @param  array  $variables
 * @param  string $require
 * @return string
 */
protected static function render_template( $default_template_path = false, $variables = array(), $require = 'once' ) {
    $template_path = locate_template( basename( $default_template_path ) );
    if ( ! $template_path ) {
	$template_path = dirname( __DIR__ ) . '/views/' . $default_template_path;
			}
	$template_path = apply_filters( 'base_template_path', $template_path );

	if ( is_file( $template_path ) ) {
            extract( $variables );
            ob_start();

            if ( 'always' == $require ) {
                require( $template_path );
            } else {
                require_once( $template_path );
            }

            $template_content = apply_filters( 'base_template_content', ob_get_clean(), $default_template_path, $template_path, $variables );
	} else {
            $template_content = '';
	}

	do_action( 'base_render_template_post', $default_template_path, $variables, $template_path, $template_content );
	return $template_content;
}

/**
 * Prepares sites to use the plugin during single or network-wide activation
 *
 *
 * @param bool $network_wide
 */
abstract public function activate( $network_wide );

/**
 * Deactivation process when de-activating the plugin
 *
 */
abstract public function deactivate();
    }
}
