<?php
/**
 * Autoload
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

if ( ! defined( 'WP_EXPRESS_ASSET_URL' ) ) {
	$dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'assets/dist';
	define( 'WP_EXPRESS_ASSET_DIR', $dir );

	$dir = explode( '/wp-content/', $dir );
	$dir = array_pop( $dir );
	$dir = content_url() . '/' . $dir;

	define( 'WP_EXPRESS_ASSET_URL', $dir );
}

include_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes/class-autoloader.php' );
$class_loader = new Sujin\Wordpress\WP_Express\Autoloader(
	'Sujin\\Wordpress\\WP_Express',
	dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes',
);
$class_loader->register();

// Browser Test
if ( 'wpexpress.test' === $_SERVER['SERVER_NAME'] ) {
	include_once( __DIR__ . DIRECTORY_SEPARATOR . '.configs' . DIRECTORY_SEPARATOR . 'phpunit' . DIRECTORY_SEPARATOR . 'class-browser-test.php' );
	new BrowserTest();
}
