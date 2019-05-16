<?php
/**
 * The base class inherited for all field types
 *
 * @project WP Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Abs_Base;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

// TODO Multiple, options/callback...
abstract class Abs_Base_Element extends Abs_Base {
	protected $_attributes = array(
		'class'       => null,
		'hidden'      => null,
		'value'       => null,
		'type'        => null,
		'placeholder' => null,
	);

	protected $_options = array(
		'help'         => null,
		'show_in_rest' => null,
		'options'      => null,
		'default'      => null,
	);

	protected $_js_callback = array(
		'on_change' => null,
		'on_blur'   => null,
		'on_focus'  => null,
	);

	public function __construct( string $name, array $attrs = array() ) {
		parent::__construct( $name );
		$this->_parse_attributes( $attrs );
		$this->add_style( WP_EXPRESS_ASSET_URL . '/meta.css', true );
	}

	public function __call( string $name, array $arguments ) {
		$is_return = empty( $arguments );
		$property  = $this->_get_property_by_child( $name );
		if ( ! empty( $property ) ) {
			if ( $is_return ) {
				return $this->{$property}[ $name ];
			}

			$this->{$property}[ $name ] = $arguments[0];
		}

		return $this;
	}

	public function _render( $maybe_post_id = null ): bool {
		$maybe_post_id = $maybe_post_id ?: null;
		$this->_refresh_attributes( $maybe_post_id );
		if ( false === $this->_is_available() ) {
			return false;
		}
		$this->_parse_attributes( $this->_defaults_attributes );
		$this->_render_wrapper_open();
		$this->_render_form();
		$this->_render_wrapper_close();
		return true;
	}

	protected abstract function _refresh_attributes( ?int $maybe_post_id = null );
	protected abstract function _is_available(): bool;
	protected abstract function _render_wrapper_open();
	protected abstract function _render_form();
	protected abstract function _render_wrapper_close();
	protected abstract function get( ?int $post_id = null );

	protected function _render_attributes() {
		foreach ( $this->_attributes as $key => $value ) {
			if ( ! empty( $value ) ) {
				echo ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
			}
		}
	}

	private function _parse_attributes( array $defaults ) {
		foreach ( $defaults as $key => $value ) {
			$property = $this->_get_property_by_child( $key );

			if ( ! empty( $property ) && ! empty( $this->{$property}[ $key ] ) ) {
				$this->{$property}[ $key ] = $value;
			}
		}
	}

	private function _get_property_by_child( string $key ): string {
		if ( array_key_exists( $key, $this->_attributes ) ) {
			return '_attributes';
		}

		if ( array_key_exists( $key, $this->_options ) ) {
			return '_options';
		}

		if ( array_key_exists( $key, $this->_js_callback ) ) {
			return '_js_callback';
		}

		return '';
	}
}