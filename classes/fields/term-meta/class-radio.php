<?php
/**
 * Interface for Fields
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express\Fields\Term_Meta;

use Sujin\Wordpress\WP_Express\Fields\Abstract_Filed_Term_Meta;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Radio;

class Radio extends Abstract_Filed_Term_Meta {
	use Trait_Radio;
}
