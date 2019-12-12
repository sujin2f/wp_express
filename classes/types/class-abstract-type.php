<?php
/**
 * Type Abstraction
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Types;

use InvalidArgumentException;

abstract class Abstract_Type {
	/**
	 * Set the value
	 * @throws InvalidArgumentException
	 */
	public function set( string $key, $value ): void {
		if ( ! property_exists( $this, $key ) ) {
			throw new InvalidArgumentException( '😡 Invalid argument' );
		}

		$this->{$key} = $value;
	}

	public function to_array(): array {
		return (array) get_object_vars( $this );
	}
}
