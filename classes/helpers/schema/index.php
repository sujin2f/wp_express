<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Schema Base
 *
 * @author     Sujin 수진 Choi <http://www.sujinc.com/>
 * @package    WP Express
 * @param      string $name Schema Unique name
 * @since      4.0.0
 * @subpackage Schema
 */

namespace Sujin\Wordpress\WP_Express\Helpers;

use Sujin\Wordpress\WP_Express\{
	Abstract_Component,
	Helpers\Schema\Property,
	Helpers\Trait_Multiton,
};

use DomainException;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Schema Base
 */
class Schema extends Abstract_Component implements JsonSerializable {
	use Trait_Multiton;

	public const REF__KEY = '$ref';

	/**
	 * JSON schema in array
	 *
	 * @var array
	 */
	private $json;

	/**
	 * Parsed properties
	 *
	 * @var Schema[]|Property[]
	 */
	private $properties = array();

	/**
	 * Parsed definitions
	 *
	 * @var Schema[]
	 */
	private $definitions = array();

	/**
	 * Required Keys
	 *
	 * @var string[]
	 */
	private $required = array();

	/**
	 * Additional properties allowed
	 *
	 * @var bool
	 */
	private $additional_properties;

	/**
	 * Get required strings
	 *
	 * @return string[]
	 */
	public function get_required(): array {
		return $this->required;
	}

	/**
	 * Get properties from $properties
	 *
	 * @return Property[]
	 */
	public function get_properties(): array {
		$properties = array();

		foreach ( $this->properties as $key => $property ) {
			if ( self::REF__KEY === $key ) {
				$properties = array_merge( $properties, $property->get_properties() );
				continue;
			}

			$properties[ $key ] = $property;
		}

		return $properties;
	}

	/**
	 * Set json from given array or string
	 *
	 * @param  array $json JSON array.
	 * @return Schema
	 */
	public function set_json( array $json ): Schema {
		if ( ! empty( $this->json ) ) {
			return $this;
		}

		$this->json = $json;
		$this->init();
		return $this;
	}

	/**
	 * Get json array
	 *
	 * @return array|null
	 */
	public function get_json(): ?array {
		return $this->json;
	}

	/**
	 * Get base dir of schema
	 *
	 * @return string
	 */
	protected static function get_base_dir(): string {
		return get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'schema';
	}

	/**
	 * Constructor
	 *
	 * Create schema from json array
	 *
	 * @param  string $key JSON key.
	 * @param  array  $json JSON array.
	 * @return Schema
	 */
	public static function from_json( string $key, array $json ): Schema {
		return self::get_instance( $key )->set_json( $json );
	}

	/**
	 * Constructor
	 *
	 * Create schema from filesystem
	 *
	 * @param  string $filename File name to read.
	 * @return Schema
	 * @throws InvalidArgumentException File does not exist.
	 * @throws DomainException          Not a valid json format.
	 */
	public static function from_file( string $filename ): Schema {
		$schema = self::get_instance( $filename );

		if ( ! empty( $schema->get_json() ) ) {
			return $schema;
		}

		global $wp_filesystem;
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();

		$json = array();
		if ( ! $wp_filesystem->exists( $filename ) ) {
			throw new InvalidArgumentException( '😡 ' . $filename . ' does not exist.' );
		}

		$json = $wp_filesystem->get_contents( $filename );
		$json = json_decode( $json, true );

		if ( json_last_error() ) {
			throw new DomainException( '😡 Not a valid json format.' );
		}

		return $schema->set_json( $json );
	}

	/**
	 * Validate and filter the value with schema
	 *
	 * @param  object|array $value value to validate.
	 * @return any[]
	 */
	public function filter( $value ): array {
		$value = (object) $value;
		$value = get_object_vars( $value );

		// For each in schema.
		foreach ( $this->get_properties() as $key => $property ) {
			// Not need to be filtered.
			if ( empty( $property->get_required() ) && empty( $value[ $key ] ) ) {
				continue;
			}

			$value[ $key ] = $property->filter( $value[ $key ] );
		}

		if ( true === $this->additional_properties ) {
			return $value;
		}

		// For each $value item, unset undefined in schema.
		foreach ( array_keys( $value ) as $key ) {
			if ( ! in_array( $key, array_keys( $this->get_properties() ), true ) ) {
				unset( $value[ $key ] );
			}
		}

		return $value;
	}

	/**
	 * Make $this->required, additional_properties, properties, definitions
	 *
	 * @used-by Schema::set_json()
	 */
	private function init(): void {
		$this->required              = $this->json['required'] ?? array();
		$this->additional_properties = $this->json['additionalProperties'] ?? false;
		$this->required              = $this->json['required'] ?? array();

		$properties  = $this->json['properties'] ?? array();
		$definitions = $this->json['definitions'] ?? array();
		$all_of      = $this->json['allOf'] ?? array();

		foreach ( $definitions as $key => $definition ) {
			$this->definitions[ $key ] = self::from_json( $this->get_name() . '/definitions/' . $key, array( 'properties' => $definition ) );
		}

		foreach ( $properties as $key => $property ) {
			// If object, create a new schema.
			if ( 'object' === ( $property['type'] ?? '' ) ) {
				$this->properties[ $key ] = self::from_json( $this->get_name() . '/properties/' . $key, $property );
				continue;
			}

			if ( self::REF__KEY === $key ) {
				$this->properties[ $key ] = $this->get_reference( $property );
				continue;
			}

			$this->properties[ $key ] = Property::from_json( $this, $key, $property );
		}

		foreach ( $all_of as $one_key => $one_value ) {
			$key   = array_key_first( $one_value );
			$value = $one_value[ $key ];

			switch ( $key ) {
				case self::REF__KEY:
					$reference        = $this->get_reference( $value );
					$this->properties = array_merge( $this->properties, $reference->get_properties() );
					$this->required   = array_merge( $this->required, $reference->get_required() );
					break;

				case 'properties':
					$one_prop         = self::from_json( $this->get_name() . '/oneof/' . $one_key, $one_value );
					$this->properties = array_merge( $this->properties, $one_prop->get_properties() );
					$this->required   = array_merge( $this->required, $one_prop->get_required() );
					break;
			}
		}
	}

	/**
	 * Returns a reference from $ref string
	 *
	 * @param   string $ref Referece to read.
	 * @return  Schema|null
	 * @used-by Schema::init()
	 * @used-by Property::init()
	 */
	public function get_reference( string $ref ): ?Schema {
		if ( 0 === strpos( $ref, '#' ) ) {
			return self::get_instance( $this->get_name() . substr( $ref, 1 ) );
		}

		if ( '.json' === substr( $ref, -5 ) ) {
			return self::from_file( static::get_base_dir() . '/' . $ref );
		}

		return null;
	}

	/**
	 * Triggered by wp_json_encode()
	 *
	 * @return array Raw .json
	 */
	public function jsonSerialize(): array {
		return $this->json;
	}
}
