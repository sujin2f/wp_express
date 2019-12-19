<?php
/**
 * Taxonomy Class
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @param   string $name The name of the componenet
 * @since   the beginning
 * @todo    manage_columns()
 * @todo    manage_custiom_columns()
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Arguments\Argument_Taxonomy;
use Sujin\Wordpress\WP_Express\Fields\Abstract_Filed_Term_Meta;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;
use Sujin\Wordpress\WP_Express\Helpers\Trait_With_Argument;

class Taxonomy extends Abstract_Component {
	use Trait_Multiton;
	use Trait_With_Argument;

	const DEFAULT_POST_TYPE = 'post';

	/**
	 * @var Post_Type[]
	 */
	private $post_types = array();

	protected function __construct( string $name ) {
		parent::__construct( $name );

		if ( 'tag' === strtolower( $name ) ) {
			$this->id = 'post_tag';
		}

		$this->argument = new Argument_Taxonomy();
		$this->argument->set( 'label', $name );

		add_action( 'init', array( $this, 'register_taxonomy' ), 25 );
	}

	public function register_taxonomy() {
		global $wp_taxonomies;

		if ( ! array_key_exists( $this->get_id(), $wp_taxonomies ) ) {
			register_taxonomy( $this->get_id(), $this->get_post_types_strings(), array_filter( $this->argument->to_array() ) );
			return;
		}

		$arguments = (array) $wp_taxonomies[ $this->get_id() ];

		## Capability
		$arguments['capabilities'] = array_keys( (array) $arguments['cap'] );

		unset( $arguments['name'] );
		unset( $arguments['cap'] );

		$user_args = array_filter(
			$this->argument->to_array(),
			function ( $value ): bool {
				return ! is_null( $value );
			}
		);

		$object_type = array_unique( array_merge( $arguments['object_type'], $this->get_post_types_strings() ) );
		unset( $arguments['object_type'] );
		register_taxonomy( $this->get_id(), $object_type, $arguments );
	}

	public function append( Abstract_Filed_Term_Meta $field ): Taxonomy {
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

	public function manage_columns() {}

	public function manage_custiom_columns() {}

	private function get_post_types_strings(): array {
		$post_types = array();

		foreach ( $this->post_types as $post_type ) {
			$post_types[] = $post_type->get_id();
		}

		return array_unique( $post_types );
	}
}
