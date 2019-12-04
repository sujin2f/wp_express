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
use Sujin\Wordpress\WP_Express\Fields\Helpers\Option;
use WP_Term;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

abstract class Abs_Base_Element extends Abs_Base {
	/**
	 * Field option
	 *
	 * @var Option
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
		$this->option = new Option();

		foreach ( $attrs as $key => $value ) {
			$this->option->{$key} = $value;
		}

		$this->add_style( WP_EXPRESS_ASSET_URL . '/meta.css', true );
		$this->init();
	}

	protected function init(): void {}

	/**
	 * Magic method for get/set option value
	 * i.g. $input->value()
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
	 * For types which has options, when they don't have any option, simply disable it.
	 */
	protected function is_available(): bool {
		return true;
	}

	public function render( ?int $id = null ): void {
		if ( false === $this->is_available() ) {
			return;
		}

		$this->refresh_id( $id );
		$this->refresh_value();
		$this->render_wrapper_open();
		$this->render_form();
		$this->render_wrapper_close();
	}

	protected abstract function refresh_id( ?int $id = null ): void;
	protected abstract function refresh_value(): void;
	protected abstract function render_wrapper_open(): void;
	protected abstract function render_form(): void;
	protected abstract function render_wrapper_close(): void;

	public function get( ?int $id = null ) {
		$this->refresh_id( $id );
		$this->refresh_value();
		return $this->value;
	}
}
