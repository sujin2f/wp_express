<?php
/**
 * Options for Form Field
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 * @todo    Data types
 */

namespace Sujin\Wordpress\WP_Express\Types;

class Field_Option extends Abstract_Type {
	private const ATTRIBUTES = array( 'class', 'hidden', 'type', 'placeholder', 'rows', 'cols' );

	// HTML attributes
	private $class;
	private $hidden;
	private $type;
	private $placeholder;
	private $rows;
	private $cols;

	private $help;
	private $show_in_rest = false;
	private $options;
	private $default;
	private $legend;
	private $single = true;
	private $on_change;
	private $on_blur;
	private $on_focus;

	public function render_attributes(): void {
		foreach ( self::ATTRIBUTES as $key ) {
			if ( ! empty( $this->{$key} ) ) {
				echo ' ' . esc_attr( $key ) . '="' . esc_attr( $this->{$key} ) . '"';
			}
		}
	}
}
