<?php
/**
 * Options for Form Field
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

class Argument_Field extends Abstract_Argument {
	private const ATTRIBUTES = array( 'class', 'hidden', 'type', 'placeholder', 'rows', 'cols' );

	/*
	 * HTML attributes
	 * @var ?string
	 */
	protected $class;
	protected function set_class( string $value ): bool {
		return true;
	}

	/*
	 * HTML attributes
	 * @var ?bool
	 */
	protected $hidden;
	protected function set_hidden( bool $value ): bool {
		return true;
	}

	/*
	 * HTML attributes
	 * @var ?string
	 */
	protected $type;
	protected function set_type( string $value ): bool {
		return true;
	}

	/*
	 * HTML attributes
	 * @var ?string
	 */
	protected $placeholder;
	protected function set_placeholder( string $value ): bool {
		return true;
	}

	/*
	 * HTML attributes
	 * @var ?int
	 */
	protected $rows;
	protected function set_rows( int $value ): bool {
		return true;
	}

	/*
	 * HTML attributes
	 * @var ?int
	 */
	protected $cols;
	protected function set_cols( int $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $help;
	protected function set_help( string $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $show_in_rest = false;
	protected function set_show_in_rest( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?array
	 */
	protected $options;
	protected function set_options( array $value ): bool {
		return true;
	}

	/*
	 * @var ?any
	 */
	protected $default;
	protected function set_default( $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $legend;
	protected function set_legend( string $value ): bool {
		return true;
	}

	/*
	 * @var bool
	 */
	protected $single = true;
	protected function set_single( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $on_change;
	protected function set_on_change( string $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $on_blur;
	protected function set_on_blur( string $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $on_focus;
	protected function set_on_focus( string $value ): bool {
		return true;
	}

	public function render_attributes(): void {
		foreach ( self::ATTRIBUTES as $key ) {
			if ( ! empty( $this->{$key} ) ) {
				echo ' ' . esc_attr( $key ) . '="' . esc_attr( $this->{$key} ) . '"';
			}
		}
	}
}
