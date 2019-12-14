<?php
/**
 * Options for Post Type
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 * @todo    Data types
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

class Argument_Post_Type extends Abstract_WP_Object_Argument {
	/*
	 * @var ?bool
	 */
	protected $can_export;
	protected function set_can_export( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $capability_type;
	protected function set_capability_type( string $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $delete_with_user;
	protected function set_delete_with_user( string $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $exclude_from_search;
	protected function set_exclude_from_search( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?bool|?string
	 */
	protected $has_archive;
	protected function set_has_archive( bool $value ): bool {
		if ( is_bool( $value ) ) {
			return true;
		}

		if ( is_string( $value ) ) {
			return true;
		}

		return false;
	}

	/*
	 * @var ?bool
	 */
	protected $map_meta_cap;
	protected function set_map_meta_cap( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $menu_icon;
	protected function set_menu_icon( string $value ): bool {
		return true;
	}

	/*
	 * @var ?int
	 */
	protected $menu_position;
	protected function set_menu_position( int $value ): bool {
		return true;
	}
	
	/*
	 * @var ?callable
	 */
	protected $register_meta_box_cb;
	protected function set_register_meta_box_cb( callable $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $show_in_admin_bar;
	protected function set_show_in_admin_bar( bool $value ): bool {
		return true;
	}

	/*
	 * @var array
	 */
	protected $supports = array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions' );
	protected function set_supports( array $value ): bool {
		return true;
	}
	
	/*
	 * @var ?string[]
	 */
	protected $taxonomies;
	protected function set_taxonomies( array $value ): bool {
		return true;
	}
}
