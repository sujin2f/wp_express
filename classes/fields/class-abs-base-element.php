<?php
/**
 * The base class inherited for all field types
 *
 * @project WP Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 * @todo Multiple, options/callback...
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Abs_Base;
use WP_Term;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

abstract class Abs_Base_Element extends Abs_Base {
	protected $attributes = array(
		'class'       => null,
		'hidden'      => null,
		'value'       => null,
		'type'        => null,
		'placeholder' => null,
		'rows'        => null,
		'cols'        => null,
	);

	protected $options = array(
		'help'         => null,
		'show_in_rest' => true,
		'options'      => null,
		'default'      => null,
		'legend'       => null,
		'single'       => true,
	);

	protected $js_callback = array(
		'on_change' => null,
		'on_blur'   => null,
		'on_focus'  => null,
	);

	protected function __construct( string $name, array $attrs = array() ) {
		parent::__construct( $name );
		$this->parse_attributes( $attrs );
		$this->add_style( WP_EXPRESS_ASSET_URL . '/meta.css', true );
	}

	public function __call( string $name, array $arguments ) {
		$is_return = empty( $arguments );
		$property  = $this->get_property_by_child( $name );
		if ( ! empty( $property ) ) {
			if ( $is_return ) {
				return $this->{$property}[ $name ];
			}

			$this->{$property}[ $name ] = $arguments[0];
		}

		return $this;
	}

	public function render( $maybe_id = null ) {
		if ( $maybe_id instanceof WP_Term ) {
			$maybe_id = $maybe_id->term_id;
		}
		$this->refresh_value( $maybe_id ?: null );
		if ( false === $this->is_available() ) {
			return;
		}
		$this->parse_attributes( $this->defaults_attributes );
		$this->render_wrapper_open();
		$this->render_form();
		$this->render_wrapper_close();
	}

	protected abstract function refresh_value( ?int $maybe_id = null );
	protected abstract function is_available(): bool;
	protected abstract function render_wrapper_open();
	protected abstract function render_form(): void;
	protected abstract function render_wrapper_close();

	protected function render_attributes() {
		foreach ( $this->attributes as $key => $value ) {
			// var_dump($key, $value);
			if ( ! empty( $value ) ) {
				echo ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
			}
		}
	}

	private function parse_attributes( array $defaults ) {
		foreach ( $defaults as $key => $value ) {
			$property = $this->get_property_by_child( $key );
			if ( empty( $this->{$property}[ $key ] ) ) {
				$this->{$property}[ $key ] = $value;
			}
		}
	}

	private function get_property_by_child( string $key ): string {
		if ( array_key_exists( $key, $this->attributes ) ) {
			return 'attributes';
		}

		if ( array_key_exists( $key, $this->options ) ) {
			return 'options';
		}

		if ( array_key_exists( $key, $this->js_callback ) ) {
			return 'js_callback';
		}

		return '';
	}

	public function get( ?int $maybe_id = null ) {
		$this->refresh_value( $maybe_id );
		return $this->attributes['value'];
	}
}
