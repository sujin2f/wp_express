<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields\Post_Meta;

use Sujin\Wordpress\WP_Express\Fields\Abs_Post_Meta_Element;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Input;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Input extends Abs_Post_Meta_Element {
	use Trait_Input;

	// Single/Multiton container
	protected static $_multiton_container  = array();
	protected static $_singleton_container = null;

	public function _register_meta() {
		$args = array(
			'type'         => 'number' === $this->_attributes['type'] ? 'integer' : 'string',
			'single'       => true,
			'show_in_rest' => true,
		);
		register_meta( 'post', $this->get_id(), $args );
	}
}
