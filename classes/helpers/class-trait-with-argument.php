<?php
/**
 * For classes which use argument
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers;

trait Trait_With_Argument {
	/*
	 * @var Abstract_Type[]
	 */
	private $arguments = array();

	/**
	 * Sets Assets_Argument properties
	 * @throws InvalidArgumentException
	 * @return self
	 */
	public function __call( string $key, array $arguments ): self {
		if ( empty( $arguments ) ) {
			throw new InvalidArgumentException( '😡 No getter supported.' );
		}

		$last_index = array_key_last( $this->assets );
		
		if ( ! $last_index ) {
			throw new InvalidArgumentException( '😡 No assets were assigned.' );
		}

		if ( ! property_exists( $this->assets[ $last_index ], $key ) ) {
			throw new InvalidArgumentException( '😡 Asset property does not exist.' );
		}

		$this->assets[ $last_index ]->{$key} = $arguments[0];
		return $this;
	}

	public function add_script( string $url ): self {
		if ( is_array( $this->manifest ) && array_key_exists( $url, $this->manifest ) ) {
}
