<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 * @todo    Register Meta
 */

namespace Sujin\Wordpress\WP_Express\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

abstract class Abs_Post_Meta_Element extends Abs_Base_Element {
	public function get( ?int $post_id = null ) {
		if ( empty( $this->_attributes['value'] ) ) {
			$this->_refresh_attributes( $post_id );
		}
		return $this->_attributes['value'];
	}

	public function update( int $post_id, $value ) {
		update_post_meta( $post_id, $this->get_id(), $value );
	}

	protected function _refresh_attributes( ?int $post_id = null ) {
		global $post;
		if ( empty( $post_id ) ) {
			if ( ! $post ) {
				return;
			}
			$post_id = $post->ID;
		}

		$this->_attributes['value'] = get_post_meta( $post_id, $this->get_id(), true );
	}

	// TODO
	protected function _render_wrapper_open() {
		$class = explode( '\\', get_called_class() );
		$class = strtolower( array_pop( $class ) );

		echo '<div id="input-wrap--' . esc_attr( $this->get_id() ) . '" class="wp_express field--' . esc_attr( $class ) . '">';
		echo '<label for="input--' . esc_attr( $this->get_id() ) . '">' . esc_html( $this->get_name() ) . '</label>';
	}

	protected function _render_wrapper_close() {
		echo '</div>';
	}
}
