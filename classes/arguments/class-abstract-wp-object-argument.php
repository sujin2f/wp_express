<?php
/**
 * Options for post type
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 * @todo    Data types
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

abstract class Abstract_WP_Object_Argument extends Abstract_Argument {
	/*
	 * @var ?string[]
	 */
	protected $capabilities;
	protected function set_capabilities( array $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $description;
	protected function set_description( string $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $hierarchical;
	protected function set_hierarchical( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $label;
	protected function set_label( string $value ): bool {
		return true;
	}

	/*
	 * @var ?array
	 */
	protected $labels;
	protected function set_labels( array $value ): bool {
		return true;
	}

	/*
	 * @var bool
	 */
	protected $public = true;
	protected function set_public( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $publicly_queryable;
	protected function set_publicly_queryable( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?string|?bool
	 */
	protected $query_var;
	protected function set_query_var( $value ): bool {
		if ( is_string( $value ) ) {
			return true;
		}

		if ( is_bool( $value ) ) {
			return true;
		}

		return false;
	}

	/*
	 * @var ?string
	 */
	protected $rest_base;
	protected function set_rest_base( string $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $rest_controller_class;
	protected function set_rest_controller_class( string $value ): bool {
		return true;
	}

	/*
	 * @var ?bool|?array
	 */
	protected $rewrite;
	protected function set_rewrite( $value ): bool {
		if ( is_bool( $value ) ) {
			return true;
		}

		if ( is_array( $value ) ) {
			return true;
		}

		return false;
	}

	/*
	 * @var ?bool
	 */
	protected $show_in_menu;
	protected function set_show_in_menu( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $show_in_nav_menus;
	protected function set_show_in_nav_menus( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $show_in_rest;

	protected function set_show_in_rest( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $show_ui;
	protected function set_show_ui( bool $value ): bool {
		return true;
	}
}
