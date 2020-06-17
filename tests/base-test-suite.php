<?php
/**
 * Test suite for unit tests
 *
 */
if ( ! class_exists( 'BaseTestSuite' ) ) {
    class BaseTestSuite extends TestSuite {
        function __construct() {
            parent::__construct();

            $this->addFile( dirname( __FILE__ ) . '/unit/test-base-module.php' );
        }
    }
}