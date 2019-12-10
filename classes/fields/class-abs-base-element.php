<?php
/**
 * The base class inherited for all field types
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @todo Multiple, options/callback...
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Abs_Base;
use Sujin\Wordpress\WP_Express\Options\Field_Option;
use WP_Term;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}
// @codeCoverageIgnoreEnd

abstract class Abs_Base_Element extends Abs_Base {
	/**
	 * Field option
	 *
	 * @var Field_Option
	 */
	protected $option;

	/**
	 * Field value
	 *
	 * @var any
	 */
	protected $value;

	/**
	 * Parent ID (post or term)
	 *
	 * @var integer
	 */
	protected $object_id;

	protected function __construct( string $name, array $attrs = array() ) {
		parent::__construct( $name );
		$this->option = new Field_Option();

		foreach ( $attrs as $key => $value ) {
			$this->option->{$key} = $value;
		}

		$this->add_style( WP_EXPRESS_ASSET_URL . '/' . self::$manifest['style.scss'], true );
		$this->add_script( WP_EXPRESS_ASSET_URL . '/' . self::$manifest['app.js'], true );
		$this->init();
	}

	/**
	 * Magic method for get/set option value
	 * i.g. $input->class( 'wide' )
	 */
	public function __call( string $key, array $arguments ): Abs_Base_Element {
		if ( ! in_array( $key, array_keys( get_object_vars( $this->option ) ), true ) ) {
			return $this;
		}

		// Return the value
		if ( empty( $arguments ) ) {
			return $this->option->{$key};
		}

		$this->option->{$key} = $arguments[0];
		return $this;
	}

	/**
	 * Get value
	 */
	public function get( ?int $id = null ) {
		$this->refresh_id( $id );
		$this->refresh_value();
		return $this->value;
	}

	/**
	 * Render the form
	 */
	public function render_form( ?int $id = null ): void {
		if ( false === $this->is_available() ) {
			return;
		}

		$this->refresh_id( $id );
		$this->refresh_value();

		$this->render_form_wrapper_open();
		$this->render_form_field();
		$this->render_form_wrapper_close();
	}

	/**
	 * For types which has options, when they don't have any option, simply disable it.
	 */
	protected function is_available(): bool {
		return true;
	}

	protected function get_called_class(): string {
		$class = explode( '\\', get_called_class() );
		return strtolower( array_pop( $class ) );
	}

	/**
	 * Get data type
	 * https://developer.wordpress.org/reference/functions/register_meta/
	 *
	 * @return string 'string', 'boolean', 'integer', 'number', 'array', or 'object'
	 */
	protected abstract function get_data_type(): string;

	protected function is_single(): bool {
		return $this->option->single;
	}

	public abstract function update( ?int $id = null, $value = null ): void;
	protected abstract function init(): void;
	protected abstract function refresh_id( ?int $id = null ): void;
	protected abstract function refresh_value(): void;
	protected abstract function render_form_wrapper_open(): void;
	protected abstract function render_form_wrapper_close(): void;
	protected abstract function render_form_field(): void;
}
