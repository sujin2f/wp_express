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
use Sujin\Wordpress\WP_Express\Options\Post_Type_Argument;
use Sujin\Wordpress\WP_Express\Fields\Abs_Post_Meta_Element;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

final class Post_Type extends Abs_Base {
	protected static $multiton_container = array();

	/**
	 * Argument
	 *
	 * @var Post_Type_Argument
	 */
	private $arguments;

	protected function __construct( string $name, array $arguments = array() ) {
		parent::__construct( $name );

		$this->arguments = new Post_Type_Argument();

		# Label
		if ( false === array_key_exists( 'label', $arguments ) ) {
			$this->arguments->label = $name;
		}

		# Supports
		if ( false === array_key_exists( 'supports', $arguments ) ) {
			$this->arguments->supports = array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions' );
		}

		foreach ( $arguments as $key => $value ) {
			$this->arguments->{$key} = $value;
		}

		add_action( 'init', array( $this, 'register_post_type' ) );
		// add_action( 'rest_api_init', array( $this, 'meta_in_rest' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
	}

	/**
	 * Set / Get argument
	 */
	public function __call( string $name, array $arguments ): Post_Type {
		if ( array_key_exists( strtolower( $name ), $this->arguments->to_array() ) ) {
			if ( empty( $arguments ) ) {
				return $this->arguments->{$name};
			}

			$this->arguments->{$name} = $arguments[0];
		}

		return $this;
	}

	/**
	 * Append meta box to this post type
	 */
	public function append( Meta_Box $metabox ): Post_Type {
		$metabox->append_to( $this );
		return $this;
	}

	/**
	 * Save Post Hook
	 * https://developer.wordpress.org/reference/hooks/save_post/
	 */
	public function save_post( int $post_id, WP_Post $post ) {
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}

		if ( $post->post_type !== $this->get_id() ) {
			return;
		}

		foreach( Abs_Post_Meta_Element::get_instances() as $post_meta ) {
			$post_meta_post_types = array_map(
				function( $post_type ) {
					return $post_type->get_id();
				},
				$post_meta->metabox->post_types,
			);

			if ( ! in_array( $this->get_id(), $post_meta_post_types, true ) ) {
				continue;
			}

			$nonce = $_POST[ $post_meta->metabox->get_id() . '_nonce' ] ?? null;

			if ( ! wp_verify_nonce( $nonce, $post_meta->metabox->get_id() ) ) {
				continue;
			}

			$post_meta->update( $post_id );
		}
	}

	public function register_post_type() {
		$arguments = (array) get_post_type_object( $this->get_id() );

		// New post type
		if ( empty( $arguments ) ) {
			register_post_type( $this->get_id(), array_filter( $this->arguments->to_array() ) );
			return;
		}

		// Capability
		$arguments['capabilities'] = array_keys( (array) $arguments['cap'] );
		unset( $arguments['cap'] );

		## Supports
		$supports              = get_all_post_type_supports( $this->get_id() );
		$arguments['supports'] = array_keys( $supports );

		$arguments = array_merge( $this->arguments->to_array(), $arguments );
		register_post_type( $this->get_id(), $arguments );
	}

/*
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
*/
}
