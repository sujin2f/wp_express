<?php
/**
 * For classes which use argument
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers;

use InvalidArgumentException;

trait Trait_With_Arguments {
	/*
	 * @var Abstract_Argument[]
	 */
	protected $arguments;

	/**
	 * Sets Abstract_Argument properties
	 * @throws InvalidArgumentException
	 * @return self
	 */
	public function __call( string $key, array $arguments ): self {
		if ( empty( $arguments ) ) {
			throw new InvalidArgumentException( '😡 No getter supported.' );
		}

		$last_index = array_key_last( $this->arguments );
		
		if ( ! $last_index ) {
			throw new InvalidArgumentException( '😡 No argument were assigned.' );
		}

		if ( ! property_exists( $this->arguments[ $last_index ], $key ) ) {
			throw new InvalidArgumentException( '😡 Asset property does not exist.' );
		}

		$this->arguments[ $last_index ]->set( $key, $arguments[0] );
		return $this;
	}
}
