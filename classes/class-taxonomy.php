<?php
/**
 * Taxonomy Class
 *
 * @project WP-Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Fields\Abs_Term_Meta_Element;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Taxonomy extends Abs_Base {
	const DEFAULT_POST_TYPE = 'post';

	// Single/Multiton container
	protected static $multiton_container  = array();

	public $is_tag = false;

	private $post_types = array();
	private $arguments  = array(
		'label'                 => null,
		'labels'                => null,
		'public'                => true,
		'publicly_queryable'    => null,
		'show_ui'               => null,
		'show_in_menu'          => null,
		'show_in_nav_menus'     => null,
		'show_in_rest'          => true,
		'rest_base'             => null,
		'rest_controller_class' => null,
		'show_tagcloud'         => null,
		'show_in_quick_edit'    => null,
		'meta_box_cb'           => null,
		'show_admin_column'     => null,
		'description'           => null,
		'hierarchical'          => null,
		'update_count_callback' => null,
		'query_var'             => null,
		'rewrite'               => null,
		'capabilities'          => null,
		'sort'                  => null,
		'_builtin'              => null,
	);
	private $user_args  = array();

	protected function __construct( string $name, array $arguments = array() ) {
		parent::__construct( $name );

		if ( 'tag' === strtolower( $name ) ) {
			$this->is_tag = 'post_tag';
		}

		$this->user_args = $arguments;

		# Label
		if ( false === array_key_exists( 'label', $arguments ) ) {
			$this->arguments['label'] = $name;
		}

		$this->arguments = array_merge( $this->arguments, $arguments );

		add_action( 'init', array( $this, 'register_taxonomy' ), 25 );
	}

	public function __call( string $name, array $arguments ) {
		if ( array_key_exists( strtolower( $name ), $this->arguments ) ) {
			if ( empty( $arguments ) ) {
				return $this->arguments[ $name ];
			}

			$this->arguments[ $name ] = $arguments[0];
			$this->user_args[ $name ] = $arguments[0];
		}

		return $this;
	}

	public function get_id(): string {
		if ( is_null( parent::get_id() ) ) {
			throw new Initialized_Exception();
		}
		return $this->is_tag ?: parent::get_id();
	}

	public function register_taxonomy() {
		global $wp_taxonomies;

		if ( ! array_key_exists( $this->get_id(), $wp_taxonomies ) ) {
			register_taxonomy( $this->get_id(), $this->get_post_types_strings(), array_filter( $this->arguments ) );
			return;
		}

		$arguments = (array) $wp_taxonomies[ $this->get_id() ];

		$object_type = array_unique( array_merge( $arguments['object_type'], $this->get_post_types_strings() ) );
		## Capability
		$arguments['capabilities'] = array_keys( (array) $arguments['cap'] );

		unset( $arguments['name'] );
		unset( $arguments['object_type'] );
		unset( $arguments['cap'] );

		register_taxonomy( $this->get_id(), $object_type, array_merge( $arguments, $this->user_args ) );
	}

	public function add( Abs_Term_Meta_Element $field ): Taxonomy {
		$field->attach_to( $this );
		return $this;
	}

	public function attach_to( $post_type ): Taxonomy {
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
			$post_types[] = ( $post_type instanceof Post_Type ) ? $post_type->get_id() : $post_type;
		}

		if ( empty( $post_types ) ) {
			$post_types = array( self::DEFAULT_POST_TYPE );
		}

		return array_unique( $post_types );
	}
}
