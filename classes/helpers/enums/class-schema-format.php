<?php
/**
 * Enum JSON schema formats
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers\Enums;

use Sujin\Wordpress\WP_Express\Helpers\Abstract_Enum;

final class Schema_Format extends Abstract_Enum {
	const URI  = array( 'uri', 'url' );
	const DATE = 'date';
}
