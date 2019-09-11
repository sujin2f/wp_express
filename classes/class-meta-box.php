<?php
/**
 * Metabox Class
 *
 * @project WP-Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Abs_Base;
use Sujin\Wordpress\WP_Express\Fields\Abs_Post_Meta_Element;
use Sujin\Wordpress\WP_Express\Enum\Metabox_Context;
use Sujin\Wordpress\WP_Express\Enum\Metabox_Priority;

use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Meta_Box extends Abs_Base {
	private const DEFAULT_POST_TYPE = 'post';

	private $_post_types = array();
	private $_context;
	private $_priority;

	public function __construct( $name ) {
		parent::__construct( $name );
		add_action( 'add_meta_boxes', array( $this, '_register_meta_box' ) );
	}

	public function add( Abs_Post_Meta_Element $field ): Meta_Box {
		$field->attach_to( $this );
		return $this;
	}

	public function attach_to( $post_type ): Meta_Box {
		$this->_post_types[] = $post_type;
		return $this;
	}

	public function context( $value ) {
		if ( in_array( $value, Metabox_Context::values() ) ) {
			$this->_context = $value;
		} else {
			$this->_context = null;
		}
	}

	public function priority( $value ) {
		if ( in_array( $value, Metabox_Priority::values() ) ) {
			$this->$_priority = $value;
		} else {
			$this->$_priority = null;
		}
	}

	public function _register_meta_box() {
		$post_types = $this->_get_parents();
		add_meta_box(
			$this->get_id(),
			$this->get_name(),
			array( $this, '_show_meta_box' ),
			$post_types,
			$this->_context ?? Metabox_Context::default(),
			$this->$_priority ?? Metabox_Priority::default(),
			array(
				'__back_compat_meta_box' => true,
			)
		);
	}

	public function _show_meta_box() {
		echo '<section class="' . esc_attr( self::PREFIX ) . ' metabox">';

		wp_nonce_field( $this->get_id(), $this->get_id() . '_nonce' );

		echo apply_filters( self::PREFIX . '_meta_box_' . $this->get_id(), '', $_GET['post'] ?? null );

		echo '</section>';
	}

	public function _get_parents(): array {
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
