<?php
/**
 * Interface for Fields
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields\Term_Meta;

use Sujin\Wordpress\WP_Express\Fields\Term_Meta_Component;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Textarea;

class Textarea extends Term_Meta_Component {
	use Trait_Textarea;
}
