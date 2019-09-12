<?php
/**
 * Gutenberg init
 *
 * @project WP Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Gutenberg extends Abs_Base {
	protected function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'init', array( $this, 'register_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_assets' ) );
	}

	public function register_assets() {

	}

	public function enqueue_assets() {

	}
}
