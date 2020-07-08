<?php
/**
 * JSON Schema Property
 *
 * @author     Sujin 수진 Choi <http://www.sujinc.com/>
 * @package    WP Express
 * @since      4.0.0
 * @subpackage Schema
 * @todo       Array Validation
 */

namespace Sujin\Wordpress\WP_Express\Helpers\Schema;

use Sujin\Wordpress\WP_Express\{
	Abstract_Component,
	Helpers\Enums\Schema_Format,
	Helpers\Enums\Schema_Type,
	Helpers\Schema,
	Helpers\Trait_Multiton,
};

use InvalidArgumentException;

/**
 * JSON Schema Property
 */
class Property extends Abstract_Component {
	use Trait_Multiton;

	/**
	 * Parent Schema
	 *
	 * @var Schema
	 */
	private $schema;

	/**
	 * Child properties
	 *
	 * @var array
	 */
	private $property;

	/**
	 * When the prop is reference
	 *
	 * @var Reference
	 */
	private $reference;

	/**
	 * Property must have type, so this can be used as an indicator of initialization.
	 *
	 * @var Schema_Type
	 */
	private $type;

	/**
	 * Format i.g. uri
	 *
	 * @var Schema_Format
	 */
	private $format;

	/**
	 * Enum
	 *
	 * @var array
	 */
	private $enum;

	/**
	 * Array type items
	 *
	 * @var array
	 */
	private $items;

	/**
	 * Is required?
	 *
	 * @var bool
	 */
	private $required;

	/**
	 * Default value
	 *
	 * @var mixed
	 */
	private $default;

	/**
	 * Required property?
	 *
	 * @used-by Property::filter()
	 */
	public function get_required(): bool {
		return $this->required;
	}

	/**
	 * Get the instance from a json
	 *
	 * @param   Schema $parent   Parent Schema.
	 * @param   string $id       Instance ID.
	 * @param   array  $property Props JSON.
	 * @return  Property
	 * @used-by Schema::init
	 */
	public static function from_json( Schema $parent, string $id, array $property ): Property {
		$that = self::get_instance( $parent->get_id() . '-' . $id );

		if ( ! $that->schema ) {
			$that->schema   = $parent;
			$that->property = $property;
			$that->init();
		}

		return $that;
	}

	/**
	 * Prepare this instance
	 */
	public function init(): void {
		$this->required = ! empty( $this->schema->get_required() ) ? in_array( $this->get_id(), $this->schema->get_required(), true ) : false;

		if ( ! empty( $this->property[ Schema::REF__KEY ] ) ) {
			$this->reference = $this->schema->get_reference( $this->property[ Schema::REF__KEY ] );
			return;
		}

		// Type is required.
		$type       = $this->property['type'];
		$this->type = Schema_Type::$type();

		$format       = $this->property['format'] ?? null;
		$this->format = ! empty( $format ) ? Schema_Format::$format() : null;

		$this->enum    = $this->property['enum'] ?? null;
		$this->items   = $this->property['items'] ?? null;
		$this->default = $this->property['default'] ?? null;
	}

	/**
	 * Filter a value
	 *
	 * @param  mixed $value Value to examine.
	 * @return mixed
	 * @throws InvalidArgumentException When the value is required but empty.
	 * @todo   $ref
	 */
	public function filter( $value ) {
		if ( $this->reference ) {
			return $this->reference->filter( $value );
		}

		// Set as default.
		if ( empty( $value ) && $this->default ) {
			return $this->default;
		}

		// Empty value, but it's required.
		if ( is_null( $value ) && $this->required ) {
			throw new InvalidArgumentException( '😡 The property \'' . $this->get_id() . '\' value is required.' );
		}

		// Filters.
		if ( ! is_null( $value ) ) {
			$value = $this->filter_type( $value );
			$value = $this->filter_enum( $value );
			$value = $this->filter_format( $value );
			$value = $this->filter_array( $value );
		}

		return $value;
	}

	/**
	 * Filter vlaue by type
	 *
	 * @param   mixed $value Value to examine.
	 * @return  mixed
	 * @used-by Property::filter()
	 */
	private function filter_type( $value ) {
		if ( empty( $this->type ) ) {
			return $value;
		}

		switch ( $this->type->case() ) {
			case Schema_Type::NUMBER:
				return (int) $value;

			case Schema_Type::BOOL:
				return (bool) $value;

			case Schema_Type::STRING:
				return (string) $value;

			case Schema_Type::ARRAY:
				return (array) $value;
		}

		return $value;
	}

	/**
	 * Filter vlaue by enum
	 *
	 * @param   mixed $value Value to examine.
	 * @return  mixed
	 * @used-by Property::filter()
	 * @throws  InvalidArgumentException Value is not matched.
	 */
	private function filter_enum( $value ) {
		if ( empty( $this->enum ) ) {
			return $value;
		}

		if ( in_array( $value, $this->enum, true ) ) {
			return $value;
		}

		throw new InvalidArgumentException( '😡 Enum value ' . $value . ' does not exist in the schema.' );
	}

	/**
	 * Filter vlaue by format
	 *
	 * @param   mixed $value Value to examine.
	 * @return  mixed
	 * @used-by Property::filter()
	 */
	private function filter_format( $value ) {
		if ( empty( $this->format ) ) {
			return $value;
		}

		if ( empty( $value ) ) {
			return $value;
		}

		switch ( $this->format->case() ) {
			case Schema_Format::URI:
				$value = filter_var( $value, FILTER_VALIDATE_URL );
				break;
			case Schema_Format::DATE:
				$value = date( 'Y-m-d', strtotime( $value ) );
				break;
		}

		return $value;
	}

	/**
	 * Filter vlaue of array
	 *
	 * @param   mixed $value Value to examine.
	 * @return  mixed
	 * @used-by Property::filter()
	 */
	private function filter_array( $value ) {
		if ( empty( $this->type ) ) {
			return $value;
		}

		if ( Schema_Type::ARRAY !== $this->type->case() ) {
			return $value;
		}

		if ( empty( $value ) ) {
			return $value;
		}

		$property = self::from_json( $this->schema, $this->get_id() . '::items', $this->items );
		$array    = array();

		foreach ( $value as $item ) {
			$array[] = $property->filter( $item );
		}

		return $array;
	}
}
