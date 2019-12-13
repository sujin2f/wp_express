<?php
/**
 * Interface for Fields
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields\Settings;

use Sujin\Wordpress\WP_Express\Fields\Abstract_Filed_Setting;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Radio;

class Radio extends Abstract_Filed_Setting {
	use Trait_Radio;
}
