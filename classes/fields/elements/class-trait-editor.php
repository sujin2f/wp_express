<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

trait Trait_Editor {
	protected $_defaults_attributes = array(
		'class' => 'regular-text',
	);

	protected function _is_available(): bool {
		return true;
	}

	protected function _render_form() {
		wp_editor( stripcslashes( $this->_attributes['value'] ), $this->get_id() );
	}
}
