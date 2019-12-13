<?php
/**
 * Enum Assets Type: script | style
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @see     https://github.com/myclabs/php-enum
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers\Enums;

use Sujin\Wordpress\WP_Express\Helpers\Abstract_Enum;

class Assets_Type extends Abstract_Enum {
	public const SCRIPT = 'script';
	public const STYLE  = 'style';
}
