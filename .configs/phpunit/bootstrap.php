<?php

/**
 * PHPUnit bootstrap file
 */

if ( ! defined( 'WPEX_PHPUNIT__DIR' ) ) {
	define( 'WPEX_PHPUNIT__DIR', dirname( __FILE__ ) );
}

if ( ! defined( 'WPEX_BASE__DIR' ) ) {
	define( 'WPEX_BASE__DIR', dirname( dirname( __DIR__ ) ) );
}

// Composer autoloader must be loaded before WP_PHPUNIT__DIR will be available
require_once dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php';

// Give access to tests_add_filter() function.
require_once getenv( 'WP_PHPUNIT__DIR' ) . '/includes/functions.php';

tests_add_filter(
	'muplugins_loaded',
	function() {
		// test set up, plugin activation, etc.
	}
);

// Start up the WP testing environment.
require getenv( 'WP_PHPUNIT__DIR' ) . '/includes/bootstrap.php';

include_once( WPEX_BASE__DIR . '/autoload.php' );
include_once( WPEX_PHPUNIT__DIR . '/class-test-case.php' );
