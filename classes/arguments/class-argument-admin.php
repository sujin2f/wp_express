<?php
/**
 * Admin Argement
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Post_Type;

class Argument_Admin extends Abstract_Argument {
	/*
	 * @var string|int|Admin|Post_Type
	 */
	protected $position = 'settings';
	protected function set_position( $value ): bool {
		if ( is_string( $value ) ) {
			return true;
		}

		if ( is_int( $value ) ) {
			return true;
		}

		if ( $value instanceof Admin ) {
			return true;
		}

		if ( $value instanceof Post_Type ) {
			return true;
		}

		return false;
	}

	/*
	 * @var string
	 */
	protected $icon = 'dashicons-admin-generic';
	protected function set_icon( string $value ): bool {
		return true;
	}

	/*
	 * @var string
	 */
	protected $capability = 'manage_options';
	protected function set_capability( string $value ): bool {
		return true;
	}

	/*
	 * @var string
	 */
	protected $plugin;
	protected function set_plugin( string $value ): bool {
		return true;
	}
}
