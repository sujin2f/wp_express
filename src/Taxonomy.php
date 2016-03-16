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

class Taxonomy extends Extensions\Abs {
	use \WE\Extensions\HtmlHelper;
	use \WE\Extensions\StoredInfoSet;

	private $arguments;
	private $post_type = 'post';

	public function __construct() {
		$name = ( !func_num_args() ) ? false : func_get_arg(0);
		parent::__construct( $name );

		$this->initOptionSetting( 'WP_TaxMeta_Settings-' . $this->key );

		$this->arguments = array(
			'name' => _x( $name, 'taxonomy general name' ),
			'singular_name' => _x( $name, 'taxonomy singular name' ),
			'search_items' =>  __( 'Search ' . $name ),
			'all_items' => __( 'All ' . $name ),
			'edit_item' => __( 'Edit ' . $name ),
			'update_item' => __( 'Update ' . $name ),
			'add_new_item' => __( 'Add New ' . $name ),
			'new_item_name' => __( 'New ' . $name . ' Name' )
		);

		$this->arguments = array(
			'labels' => $this->arguments,
			'hierarchical' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => 'rewrite'
		);

		add_action( 'init', array( $this, 'registerTaxonomy' ), 100 );
	}

	public function __get( $name ) {
		return $this->getOptionSetting( $name );
	}

	public function __set( $name, $value ) {
		if ( parent::__set( $name, $value ) ) return;
		if ( $this->setOptionSetting( $name, $value ) ) return;

		switch( $name ) {
			case 'hierarchical' :
			case 'show_ui' :
			case 'query_var' :
			case 'rewrite' :
				$this->arguments[ $name ] = $value;
				return;
			break;

			case 'post_type' :
			case 'posttype' :
			case 'post' :
				$this->post_type = sanitize_title( $value );
				return;
			break;
		}
	}

	public function registerTaxonomy() {
		if ( $this->post_type && array_key_exists( $this->post_type, get_post_types() ) ) {
			if ( $this->arguments[ 'rewrite' ] == 'rewrite' ) $this->arguments[ 'rewrite' ] = array( 'slug' => $this->key );
			register_taxonomy( $this->key, array( $this->post_type ), $this->arguments );

			// Meta Edit & Save
			add_action( "{$this->key}_edit_form", array( $this, 'printTermMeta' ), 10 );
			add_action( "edited_{$this->key}", array( $this, 'saveMetas' ), 10 );
		}
	}

	private function isSupported() {
		global $wp_version;
		return ( version_compare( $wp_version, '4.4', '<' ) ) ? false : true;
	}

	private function readFromDb( $term_id ) {
		if ( $this->value = get_term_meta( $term_id, '_' . $this->key . '_', true ) ) {
			foreach( $this->options as $key => $option ) {
				if ( array_key_exists( $key, $this->values ) )
					$option->value = $this->values[ $key ];
			}
		}
	}

	public function printTermMeta( $term ) {
		if ( !$this->isSupported() ) {
			$this->showMessage( 'In order to use term meta function, you must update your Wordpress to at least up to 4.4 version.', 'error' );
			return;
		}

		if ( !$this->options ) return false;

		$this->readFromDb( $term->term_id );

		printf( '<table class="form-table" id="term-%s"><tbody>', $this->key );

		foreach( $this->sections as $fields ) {
			foreach( $fields[ 'fields' ] as $field ) {
				echo '<tr>';
						if ( is_array( $field ) ) {	// Set Item
							printf( '<th scope="row" valign="top"><label for="%s">%s</label></th>', $this->options[ $field[0] ]->key, $this->options[ $field[0] ]->name );
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
		}

		echo '</tbody></table>';
	}

	public function saveMetas( $term_id ) {
		if ( !$this->options ) return;

		$metas = [];
		foreach( $this->options as $option ) {
			if ( $option->type === 'set' ) continue;

			$metas[ $option->key ] = $_POST[ $option->key ];
		}

		update_term_meta( $term_id, '_' . $this->key . '_', $metas );
	}
}