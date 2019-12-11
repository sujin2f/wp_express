<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields\Settings;

use Sujin\Wordpress\WP_Express\Fields\Setting_Component;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Checkbox;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}
// @codeCoverageIgnoreEnd

class Checkbox extends Setting_Component {
	use Trait_Checkbox;
}
