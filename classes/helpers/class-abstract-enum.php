<?php
/**
 * Enum Base
 *
 * Minimized or customized from https://github.com/myclabs/php-enum
 * Usuage:
 * ```
 * class Enum extends Abstract_Enum {
 * 	public const KEY = 'value';
 * }
 * 
 * Enum::value();
 * ```
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @see     https://github.com/myclabs/php-enum
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers;

use ReflectionClass;
use InvalidArgumentException;

abstract class Abstract_Enum {
	/**
	 * Store existing constants in a static cache per object.
	 * Key is the class, and the value is an array which is const value => Enum instance.
	 *
	 * @var array
	 */
	protected static $cache = array();

	/**
	 * Selected const key
	 *
	 * @var string
	 */
	protected $const_key;

	private function __construct( string $const_key ) {
		$this->const_key = $const_key;
	}

	/**
	 * Get the value of the instance
	 * This will return array when the const is array
	 *
	 * <code>
	 * <?php
	 * $integer = Enum::int();
	 *
	 * switch ( $integer->case() ) {
	 *     case Number::FLOAT:
	 *         do_somthing();
	 *         break;
	 *
	 *     case Number::INT:
	 *         do_somthing();
	 *         break;
	 * }
	 * ?>
	 * </code>
	 *
	 * @return string|string[]
	 */
	public function case() {
		return constant( get_called_class() . '::' . $this->const_key );
	}

	/**
	 * Get the instance from a string
	 * @throws InvalidArgumentException
	 * @return self
	 */
	public static function __callStatic( string $value, array $_ ): self {
		self::to_array();
		$class = get_called_class();

		if ( ! array_key_exists( $value, self::$cache[ $class ] ) ) {
			throw new InvalidArgumentException( '😡 ' . $value . ' is not valid in Enum' );
		}

		return self::$cache[ $class ][ $value ];
	}

	/**
	 * Check the value in this consts
	 */
	public static function in_array( string $value ): bool {
		self::to_array();
		$class = get_called_class();
		return array_key_exists( $value, self::$cache[ $class ] );
	}

	/**
	 * Make const to array cache
	 * @uses   ReflectionClass
	 */
	private static function to_array(): void {
		$class = get_called_class();

		if ( array_key_exists( $class, self::$cache )  ) {
			return;
		}

		self::$cache[ $class ] = array();
		
		$reflection = new ReflectionClass( $class );
		$constants  = $reflection->getConstants();

		foreach ( $constants as $key => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $array_value ) {
					$enum = new static( $key );
					self::$cache[ $class ][ $array_value ] = $enum;
				}
				continue;
			}

			$enum = new static( $key );
			self::$cache[ $class ][ $value ] = $enum;
		}
	}
}
