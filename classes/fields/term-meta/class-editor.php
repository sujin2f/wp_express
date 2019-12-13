<?php
/**
 * Interface for Fields
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields\Term_Meta;

use Sujin\Wordpress\WP_Express\Fields\Abstract_Filed_Term_Meta;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Editor;

class Editor extends Abstract_Filed_Term_Meta {
	use Trait_Editor;
}
