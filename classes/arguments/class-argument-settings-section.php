<?php
/**
 * Options for Settings_Section
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Post_Type;

class Argument_Settings_Section extends Abstract_Argument {
	/*
	 * @var string|Admin
	*/
	protected $admin_page = 'general';
	protected function set_admin_page( $value ): bool {
		if ( is_string( $value ) ) {
			return true;
		}

		if ( $value instanceof Admin ) {
			return true;
		}

		return false;
	}
}
