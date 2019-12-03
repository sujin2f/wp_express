<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields\Settings;

use Sujin\Wordpress\WP_Express\Fields\Abs_Setting_Element;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Input;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Input extends Abs_Setting_Element {
	use Trait_Input;

	// Single/Multiton container
	protected static $multiton_container  = array();
	protected static $singleton_container = null;
}
