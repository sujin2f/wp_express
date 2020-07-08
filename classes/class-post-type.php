<?php
/**
 * Create a new Post Type
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @param   string $name The name of the componenet
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Arguments\Argument_Post_Type;
use Sujin\Wordpress\WP_Express\Fields\Abstract_Filed_Post_Meta;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;
use Sujin\Wordpress\WP_Express\Helpers\Trait_With_Argument;
use WP_Post;

class Post_Type extends Abstract_Component {
	use Trait_Multiton;
	use Trait_With_Argument;

	protected function __construct( string $name ) {
		parent::__construct( $name );

		$this->argument = new Argument_Post_Type();
		$this->argument->set( 'label', $name );

		add_action( 'init', array( $this, 'register_post_type' ) );
		// add_action( 'rest_api_init', array( $this, 'meta_in_rest' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
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

		foreach ( Abstract_Filed_Post_Meta::get_instances() as $post_meta ) {
			if ( empty( $post_meta->metabox ) ) {
				continue;
			}

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
			register_post_type( $this->get_id(), array_filter( $this->argument->to_array() ) );
			return;
		}

		// Capability
		$arguments['capabilities'] = array_keys( (array) $arguments['cap'] );
		unset( $arguments['cap'] );

		## Supports
		$supports              = get_all_post_type_supports( $this->get_id() );
		$arguments['supports'] = array_keys( $supports );
		$user_args             = array_filter(
			$this->argument->to_array(),
			function ( $value ): bool {
				return ! is_null( $value );
			}
		);
		$arguments             = array_merge( $arguments, $user_args );
		register_post_type( $this->get_id(), $arguments );
	}
}
