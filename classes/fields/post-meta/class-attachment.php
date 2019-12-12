<?php
/**
 * Interface for Fields
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields\Post_Meta;

use Sujin\Wordpress\WP_Express\Fields\Post_Meta_Component;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Attachment;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Attachment extends Post_Meta_Component {
	use Trait_Attachment;
}
