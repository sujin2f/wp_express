<?php
/**
 * Enum Abs
 *
 * @project WP Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Enum;

use ReflectionClass;

abstract class Enum {
	protected static $cache = array();

	// Prevent to create an instance
	private function __construct() {}

	public static function to_array(): array {
		$class = get_called_class();

		if ( ! isset( static::$cache[ $class ] ) ) {
			$reflection              = new ReflectionClass( $class );
			static::$cache[ $class ] = $reflection->getConstants();
		}

		return static::$cache[ $class ];
	}

	public static function default() {
		$members = static::to_array();
		$values  = array_values( $members );

		return $values[0];
	}

	public static function values() {
		$members = static::to_array();
		return array_values( $members );
	}
}
