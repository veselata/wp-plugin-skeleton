<?php

/**
 * PHPUnit bootstrap file
 *
 * @package WP Test
 */

$base = __DIR__ . '/../../../../';

if ( file_exists( $base . '/vendor/autoload.php' ) ) {
    require_once $base . '/vendor/autoload.php';
}

require_once( __DIR__ . '/../classes/base-module.php' );

\WP_Mock::bootstrap();


