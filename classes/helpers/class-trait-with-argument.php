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

trait Trait_With_Argument {
	/*
	 * @var Abstract_Argument
	 */
	protected $argument;

	/**
	 * Sets Abstract_Argument properties
	 * @throws InvalidArgumentException
	 * @return self
	 */
	public function __call( string $key, array $arguments ): self {
		if ( empty( $arguments ) ) {
			throw new InvalidArgumentException( '😡 No getter supported, or your called unregistered method.' );
		}

		if ( ! $this->argument->has( $key ) ) {
			throw new InvalidArgumentException( '😡 Argument does not exist.' );
		}

		$this->argument->set( $key, $arguments[0] );
		return $this;
	}
}
