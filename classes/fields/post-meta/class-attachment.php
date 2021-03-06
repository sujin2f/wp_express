<?php
/**
 * Interface for Fields
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express\Fields\Post_Meta;

use Sujin\Wordpress\WP_Express\Fields\Abstract_Filed_Post_Meta;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Attachment;

class Attachment extends Abstract_Filed_Post_Meta {
	use Trait_Attachment;
}
