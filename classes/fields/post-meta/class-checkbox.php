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
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Checkbox;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Checkbox extends Abs_Post_Meta_Element {
	use Trait_Checkbox;

	protected function render_wrapper_open() {
		echo '<div id="input-wrap--' . esc_attr( $this->get_id() ) . '" class="wp_express--field--checkbox">';
		echo '<label for="input--' . esc_attr( $this->get_id() ) . '">';
	}

	protected function render_wrapper_close() {
		echo esc_html( $this->get_name() ) . '</label></div>';
	}
}
