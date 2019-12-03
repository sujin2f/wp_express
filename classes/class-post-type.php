<?php
/**
 * Create a new Post Type
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Meta_Box;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Post_Type extends Abs_Base {
	// Single/Multiton container
	protected static $multiton_container  = array();
	protected static $singleton_container = null;

	private $arguments = array(
		'label'                 => null,
		'labels'                => null,
		'description'           => null,
		'public'                => true,
		'exclude_from_search'   => null,
		'publicly_queryable'    => null,
		'show_ui'               => null,
		'show_in_nav_menus'     => null,
		'show_in_menu'          => null,
		'show_in_admin_bar'     => null,
		'menu_position'         => null,
		'menu_icon'             => null,
		'capability_type'       => null,
		'capabilities'          => null,
		'map_meta_cap'          => null,
		'hierarchical'          => null,
		'supports'              => null,
		'register_meta_box_cb'  => null,
		'taxonomies'            => null,
		'has_archive'           => null,
		'rewrite'               => null,
		'permalink_epmask'      => null,
		'query_var'             => null,
		'can_export'            => null,
		'delete_with_user'      => null,
		'show_in_rest'          => true,
		'rest_base'             => null,
		'rest_controller_class' => null,
		'_builtin'              => null,
		'_edit_link'            => null,
	);

	private $user_args = array();

	protected function __construct( string $name, array $arguments = array() ) {
		parent::__construct( $name );

		$this->user_args = $arguments;

		# Label
		if ( false === array_key_exists( 'label', $arguments ) ) {
			$this->arguments['label'] = $name;
		}

		# Supports
		if ( false === array_key_exists( 'supports', $arguments ) ) {
			$this->arguments['supports'] = array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions' );
		}

		$this->arguments = array_merge( $this->arguments, $arguments );

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'rest_api_init', array( $this, 'meta_in_rest' ) );
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

	public function add( Meta_Box $metabox ): Post_Type {
		$metabox->post_type( $this );
		return $this;
	}

	public function register_post_type() {
		$arguments = (array) get_post_type_object( $this->get_id() );

		if ( empty( $arguments ) ) {
			register_post_type( $this->get_id(), array_filter( $this->arguments ) );
			return;
		}

		## Capability
		$arguments['capabilities'] = array_keys( (array) $arguments['cap'] );
		unset( $arguments['cap'] );

		## Supports
		$supports              = get_all_post_type_supports( $this->get_id() );
		$arguments['supports'] = array_keys( $supports );

		$arguments = array_merge( $arguments, $this->user_args );
		register_post_type( $this->get_id(), $arguments );
	}

	public function meta_in_rest() {
		register_rest_field(
			$this->get_id(),
			'meta',
			array(
				'get_callback' => array( $this, 'get_post_meta' ),
				'schema'       => null,
			)
		);
	}

	public function get_post_meta( $object = '', $field_name = '', $_ = array() ) {
		global $wp_meta_keys;
		$meta = get_post_meta( $object['id'] );
		foreach ( array_keys( $meta ) as $key ) {
			$registered   = $wp_meta_keys['post'][''][ $key ] ?? array();
			$is_single    = $registered['single'] ?? false;
			$show_in_rest = $registered['show_in_rest'] ?? false;

			if ( ! $show_in_rest ) {
				unset( $meta[ $key ] );
				continue;
			}

			if ( $is_single ) {
				$meta[ $key ] = $meta[ $key ][0];
			}
		}
		return $meta;
	}
}
