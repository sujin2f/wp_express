<?php
/**
 * Options for Form Field
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 * @todo    Data types
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

class Argument_Field extends Abstract_Argument {
	private const ATTRIBUTES = array( 'class', 'hidden', 'type', 'placeholder', 'rows', 'cols' );

	// HTML attributes
	protected $class;
	protected $hidden;
	protected $type;
	protected $placeholder;
	protected $rows;
	protected $cols;

	protected $help;
	protected $show_in_rest = false;
	protected $options;
	protected $default;
	protected $legend;

	/*
	 * @var bool
	 */
	protected $single = true;
	protected $on_change;
	protected $on_blur;
	protected $on_focus;

	public function render_attributes(): void {
		foreach ( self::ATTRIBUTES as $key ) {
			if ( ! empty( $this->{$key} ) ) {
				echo ' ' . esc_attr( $key ) . '="' . esc_attr( $this->{$key} ) . '"';
			}
		}
	}
}
