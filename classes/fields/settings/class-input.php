<?php
/**
 * Interface for Fields
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields\Settings;

use Sujin\Wordpress\WP_Express\Fields\Setting_Component;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Input;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}
// @codeCoverageIgnoreEnd

class Input extends Setting_Component {
	use Trait_Input;
}
