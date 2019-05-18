<?php
/**
 * Metabox Class
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Abs_Base;
use Sujin\Wordpress\WP_Express\Fields\Abs_Post_Meta_Element;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Meta_Box extends Abs_Base {
	private const DEFAULT_POST_TYPE = 'post';

	public const POST_TYPE = 'post_type';
	private $_post_types   = array();
	private $_fields       = array();

	public function __construct( $name ) {
		parent::__construct( $name );
		add_action( 'add_meta_boxes', array( $this, '_register_meta_box' ) );
		add_action( 'save_post', array( $this, '_save_post' ), 10, 2 );
	}

	public function __call( string $name, array $arguments ): Meta_Box {
		switch ( strtolower( $name ) ) {
			case self::POST_TYPE:
				if ( empty( $arguments ) ) {
					return $this->_post_types;
				}

				$this->_post_types[] = $arguments[0];
				break;
		}

		return $this;
	}

	public function _register_meta_box() {
		$post_types = $this->_get_post_types_strings();
		add_meta_box( $this->get_id(), $this->get_name(), array( $this, '_show_meta_box' ), $post_types );
	}

	public function add( Abs_Post_Meta_Element $field ): Meta_Box {
		$this->_fields[] = $field;
		return $this;
	}

	public function _show_meta_box() {
		echo '<section class="' . esc_attr( self::PREFIX ) . ' metabox">';

		wp_nonce_field( $this->get_id(), $this->get_id() . '_nonce' );

		foreach ( $this->_fields as $field ) {
			$field->_render();
		}

		echo '</section>';
	}

	public function _save_post( int $post_id, WP_Post $post ) {
		$nonce = $_POST[ $this->get_id() . '_nonce' ] ?? null;

		if ( ! wp_verify_nonce( $nonce, $this->get_id() ) ) {
			return;
		}

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}

		foreach ( $this->_get_post_types_strings() as $post_type ) {
			if ( $post->post_type === $post_type ) {
				foreach ( $this->_fields as $field ) {
					$field->_update( $post_id, $_POST[ $field->get_id() ] );
				}
			}
		}
	}

	private function _get_post_types_strings(): array {
		$post_types = array();

		foreach ( $this->_post_types as $post_type ) {
			$post_types[] = ( $post_type instanceof Post_Type ) ? $post_type->get_id() : $post_type;
		}

		if ( empty( $post_types ) ) {
			$post_types = array( self::DEFAULT_POST_TYPE );
		}

		return $post_types;
	}
}
