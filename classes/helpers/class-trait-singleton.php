<?php
/**
 * Class : Instancable
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers;

trait Trait_Singleton {
	/**
	 * @var self
	 */
	protected static $singleton_container = null;

	public static function get_instance(): self {
		if ( is_null( static::$singleton_container ) ) {
			static::$singleton_container = new static();
		}

		return static::$singleton_container;
	}
}
