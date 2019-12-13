<?php
/**
 * Options for Meta_Box
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 * @todo    Data types
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

use Sujin\Wordpress\WP_Express\Helpers\Enums\{
	Meta_Box_Context,
	Meta_Box_Priority,
};

class Argument_Meta_Box extends Abstract_Argument {
	/*
	 * @var Meta_Box_Context
	 */
	protected $context = Meta_Box_Context::ADVANCED;

	/*
	 * @var Meta_Box_Priority
	 */
	protected $priority = Meta_Box_Priority::DEFAULT;
}
