<?php
/**
 * Enum Admin Position
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers\Enums;

use Sujin\Wordpress\WP_Express\Helpers\Abstract_Enum;

class Meta_Box_Context extends Abstract_Enum {
	public const NORMAL   = 'normal';
	public const SIDE     = 'side';
	public const ADVANCED = 'advanced';
}
