<?php
/**
 * Enum JSON schema property type
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers\Enums;

use Sujin\Wordpress\WP_Express\Helpers\Abstract_Enum;

final class Schema_Type extends Abstract_Enum {
	const STRING = 'string';
	const NUMBER = 'number';
	const OBJECT = 'object';
	const ARRAY  = 'array';
	const BOOL   = 'boolean';
	const NULL   = 'null';
}
