<?php
/**
 * Type Abstraction
 *
 * If the method `protected function set_{attribute}( type $value ): bool { return true; }` exist,
 * you can test the type of the attribute.
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

use InvalidArgumentException;

abstract class Abstract_Argument {
	/**
	 * Set the value
	 * @throws InvalidArgumentException
	 */
	public function set( string $key, $value ): void {
		if ( ! $this->has( $key ) ) {
			throw new InvalidArgumentException( '😡 Invalid argument' );
		}

		if ( false === $this->{'set_' . $key}( $value ) ) {
			throw new InvalidArgumentException( '😡 Data type is not matched' );
		}

		$this->{$key} = $value;
	}

	/**
	 * Get the value
	 * @throws InvalidArgumentException
	 */
	public function get( string $key ) {
		if ( ! $this->has( $key ) ) {
			throw new InvalidArgumentException( '😡 Invalid argument' );
		}

		return $this->{$key};
	}

	public function to_array(): array {
		return (array) get_object_vars( $this );
	}

	public function has( string $key ): bool {
		return property_exists( $this, $key );
	}
}
