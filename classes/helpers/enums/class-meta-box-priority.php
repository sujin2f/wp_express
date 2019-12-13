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

class Meta_Box_Priority extends Abstract_Enum {
	public const HIGH    = 'high';
	public const LOW     = 'low';
	public const DEFAULT = 'default';
}
