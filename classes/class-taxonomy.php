<?php
/**
 * Taxonomy Class
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @param   ?string $name The name of the componenet
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Fields\Term_Meta_Component;
use Sujin\Wordpress\WP_Express\Types\Taxonomy_Argument;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;

class Taxonomy extends Component {
	use Trait_Multiton;

	const DEFAULT_POST_TYPE = 'post';

	/**
	 * @var Post_Type[]
	 */
	private $post_types = array();

	/**
	 * @var Taxonomy_Argument
	 */
	private $arguments;

	protected function __construct( string $name, array $arguments = array() ) {
		parent::__construct( $name );

		if ( 'tag' === strtolower( $name ) ) {
			$this->id = 'post_tag';
		}

		$this->arguments = new Taxonomy_Argument();

		# Label
		if ( false === array_key_exists( 'label', $arguments ) ) {
			$this->arguments->label = $name;
		}

		foreach ( $arguments as $key => $value ) {
			$this->arguments->{$key} = $value;
		}

		add_action( 'init', array( $this, 'register_taxonomy' ), 25 );
	}

	/**
	 * @return any|Post_Type
	 */
	public function __call( string $name, array $arguments ) {
		if ( array_key_exists( strtolower( $name ), $this->arguments->to_array() ) ) {
			if ( empty( $arguments ) ) {
				return $this->arguments->{$name};
			}

			$this->arguments->{$name} = $arguments[0];
		}

		return $this;
	}

	public function register_taxonomy() {
		global $wp_taxonomies;

		if ( ! array_key_exists( $this->get_id(), $wp_taxonomies ) ) {
			register_taxonomy( $this->get_id(), $this->get_post_types_strings(), array_filter( $this->arguments->to_array() ) );
			return;
		}

		$arguments = (array) $wp_taxonomies[ $this->get_id() ];

		## Capability
		$arguments['capabilities'] = array_keys( (array) $arguments['cap'] );

		unset( $arguments['name'] );
		unset( $arguments['cap'] );

		$user_args = array_filter( 
			$this->arguments->to_array(),
			function ( $value ): bool {
				return ! is_null( $value );
			}
		);

		$object_type = array_unique( array_merge( $arguments['object_type'], $this->get_post_types_strings() ) );
		unset( $arguments['object_type'] );
		register_taxonomy( $this->get_id(), $object_type, $arguments );
	}

	public function append( Term_Meta_Component $field ): Taxonomy {
		$field->append_to( $this );
		return $this;
	}

	public function append_to( $post_type ): Taxonomy {
		if ( in_array( $post_type, $this->post_types ) ) {
			return $this;
		}
		$this->post_types[] = $post_type;
		return $this;
	}

	// TODO
	public function manage_columns() {
	}

	// TODO
	public function manage_custiom_columns() {
	}

	private function get_post_types_strings(): array {
		$post_types = array();

		foreach ( $this->post_types as $post_type ) {
			$post_types[] = $post_type->get_id();
		}

		return array_unique( $post_types );
	}
}
