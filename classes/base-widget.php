<?php

if ( ! class_exists( 'Base_Widget' ) ) {

/**
 * Handles shortcode settings
 */
class Base_Widget extends \WP_Widget  {
    
 /**
 * Constructor
 *
 */
function __construct() {
    parent::__construct('display_base', __( 'Display widget' ), array(
	'description' => __( 'Display widget description' ),
    ));
}
  
/**
 * Widget content
 * @param type $args
 * @param type $instance
 */
public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );
  
    // before and after widget arguments are defined by themes
    echo $args['before_widget'];
    if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];
  
    // This is where you run the code and display the output
    echo __( 'Hello, Base Widget!');
    echo $args['after_widget'];
}
          
/**
 * Output the front-end
 * 
 * @param type $instance
 */
public function form( $instance ) {}
      
/**
 * Update instance of arguments with new onces
 * 
 * @param type $new_instance
 * @param type $old_instance
 */
public function update( $new_instance, $old_instance ) {}

}
}