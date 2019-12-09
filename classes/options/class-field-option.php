<?php
/**
 * Options for field
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Options;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Field_Option {
	// HTML attributes
	public $class;
	public $hidden;
	public $type;
	public $placeholder;
	public $rows;
	public $cols;

	public $help;
	public $show_in_rest = false;
	public $options;
	public $default;
	public $legend;
	public $single = true;
	public $on_change;
	public $on_blur;
	public $on_focus;

	private const ATTRIBUTES = array( 'class', 'hidden', 'type', 'placeholder', 'rows', 'cols' );

	public function render_attributes(): void {
		foreach ( self::ATTRIBUTES as $key ) {
			if ( ! empty( $this->{$key} ) ) {
				echo ' ' . esc_attr( $key ) . '="' . esc_attr( $this->{$key} ) . '"';
			}
		}
	}
}
