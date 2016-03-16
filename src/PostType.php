<?php
/**
 *
 * WE\PostType Class
 *
 * @author	Sujin 수진 Choi
 * @package	wp-express
 * @version	4.0.0
 * @website	http://sujinc.com
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice
 *
 */

namespace WE;

if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class PostType extends Extensions\Abs {
	use \WE\Extensions\StoredInfoSet;

	private $arguments;
	private $taxonomies = [];

	public function __construct() {
		$name = ( !func_num_args() ) ? false : func_get_arg(0);
		parent::__construct( $name );

		$this->initOptionSetting( 'WP_PostMeta_Settings-' . $this->key );

		$labels = array(
			'name' => _x( $this->name, 'post type general name' ),
			'singular_name' => _x( $name, 'post type singular name' ),
			'add_new' => _x( 'Add New', 'project' ),
			'add_new_item' => __( 'Add New ' . $name ),
			'edit_item' => __( 'Edit ' . $name ),
			'new_item' => __( 'New ' . $name ),
			'view_item' => __( 'View ' . $name ),
			'search_items' => __( 'Search ' . $name ),
			'not_found' =>  __( 'No ' . $name . ' found' ),
			'not_found_in_trash' => __( 'No ' . $name . ' found in Trash' ),
			'parent_item_colon' => '',
			'menu_name' => $name
		);

		$this->arguments = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => 'rewrite',
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => [ 'title' ]
		);

		add_action( 'init', array( $this, 'registerPostType' ) );
		add_action( 'admin_head', array( $this, 'registerMetaBoxes' ) );

		add_action( 'parse_query', array( $this, 'parseQuery' ) );
		add_action( 'save_post', array( $this, 'savePost' ), 10, 2 );
	}

	public function __call( $name, $arguments ) {
		if ( strpos( $name, 'PostMetaBox_' ) === 0 ) {
			$this->echoPostMetaBox( substr( $name, 12 ) );
		}
	}

	public function __get( $name ) {
		if ( $return = $this->getOptionSetting( $name ) ) return $return;

		switch( $name ) {
			case 'taxonomy';
				if ( !$this->taxonomies ) return false;

				end( $this->taxonomies );
				$key = key( $this->taxonomies );
				return  $this->taxonomies[ $key ];
			break;
		}
	}

	public function __set( $name, $value ) {
		if ( parent::__set( $name, $value ) ) return;
		if ( $this->setOptionSetting( $name, $value ) ) return;

		switch( $name ) {
			case 'public' :
			case 'publicly_queryable' :
			case 'show_ui' :
			case 'show_in_menu' :
			case 'query_var' :
			case 'rewrite' :
			case 'capability_type' :
			case 'has_archive' :
			case 'hierarchical' :
			case 'menu_position' :
				$this->arguments[ $name ] = $value;
			break;

			case 'supports' :
				$this->arguments[ 'supports' ][] = $value;
			break;

			case 'taxonomy';
				if ( gettype( $value ) === 'object' && get_class( $value ) === 'WE\\Taxonomy' ) {
					$value->post_type = $this->key;
					$this->taxonomies[ $value->key ] = $value;

				} else if( gettype( $value ) === 'string' ) {
					$key = sanitize_title( $value );
					$this->taxonomies[ $key ] = new Taxonomy( $value );
					$this->taxonomies[ $key ]->post_type = $this->key;
				}
			break;
		}
	}

	public function registerPostType() {
		// Post Type
		if ( $this->arguments[ 'rewrite' ] == 'rewrite' ) $this->arguments[ 'rewrite' ] = array( 'slug' => $this->key );
		register_post_type( $this->key, $this->arguments );
	}

	public function registerMetaBoxes() {
		if ( !empty( $this->sections ) ) {
			foreach( $this->sections as $sectionKey => $section ) {
				add_meta_box( $sectionKey, $section[ 'name' ], array( $this, 'PostMetaBox_' . $sectionKey ), $this->key );
			}
		}
	}

	private function readFromDb() {
		global $post;

		if ( gettype( $post ) !== 'object' && get_class( $post ) !== 'WP_Post' ) return;
		if ( !$post ) return;
		if ( $post->post_type !== $this->key ) return;

 		if ( !$this->values && $this->values = get_post_meta( $post->ID, '_' . $this->key . '_', true ) ) {
			foreach( $this->options as $key => $option ) {
				if ( array_key_exists( $key, $this->values ) )
					$option->value = $this->values[ $key ];
			}
 		}
	}

	public function echoPostMetaBox( $sectionKey ) {
		$this->readFromDb();

		echo '<table class="form-table">';
		foreach( $this->sections[ $sectionKey ][ 'fields' ] as $field ) {
			echo '<tr>';
					if ( is_array( $field ) ) {	// Set Item
						printf( '<th>%s</th>', $this->options[ $field[0] ]->name );
						array_shift( $field );

						echo '<td>';

						foreach( $field as $setFieldKey ) {
							$this->options[ $setFieldKey ]->printSettingsField();
						}
						echo '</td>';

					} else {
						printf( '<th>%s</th>', $this->options[ $field ]->name );

						echo '<td>';
						$this->options[ $field ]->printSettingsField();
						echo '</td>';
					}
			echo '</tr>';
		}
		echo '</table>';
	}

	private function checkIsSaving() {
		if( !$_POST ) return false;
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST[ 'bulk_edit' ] ) ) return;
		if ( !isset( $_POST[ 'post_type' ] ) || $_POST[ 'post_type' ] != $this->key ) return;

		delete_transient( $this->transientKey );
		return true;
	}

	public function savePost( $post_id, $post ) {
		if( !$this->isSaving ) return;

		$metas = [];
		foreach( $this->options as $option ) {
			if ( $option->type === 'set' ) continue;

			$metas[ $option->key ] = $_POST[ $option->key ];
		}

		update_post_meta( $post_id, '_' . $this->key . '_', $metas );
	}

	public function parseQuery( $query ) {
		if ( !$query->is_main_query() ) return false;

		if ( is_single() && !empty( $query->query[ 'post_type' ] ) && $query->query[ 'post_type' ] == $this->key ) {
			if ( !$query->is_posttype ) {
				$query->is_posttype = array();
			}
			$query->is_posttype[ $this->key ] = true;
		}

		# add the slug to the body class
		if ( $query->is_posttype[ $this->key ] )
			add_filter( 'body_class', array( $this, 'themeBodyClass' ) );
	}

	public function themeBodyClass( $classes ) {
		$classes[] = 'template-' . $this->key;
		return $classes;
	}
}