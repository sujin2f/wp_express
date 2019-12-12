<?php
/**
 * Multiton Helper
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers;

trait Trait_Multiton {
	/**
	 * @var self[]
	 */
	protected static $multiton_container  = array();

	public static function get_instance( ...$args ): self {
		$id  = $args[0];
		$key = md5( $id );
		if ( ! array_key_exists( $key, static::$multiton_container ) ) {
			static::$multiton_container[ $key ] = new static( ...$args );
		}
		return static::$multiton_container[ $key ];
	}

	/*
	 * Gets multiton instance
	 * @return self[]
	 */
	public static function get_instances(): array {
		return static::$multiton_container;
	}
}
