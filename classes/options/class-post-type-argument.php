<?php
/**
 * Options for post type
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

class Post_Type_Argument {
	public $label;
	public $labels;
	public $description;
	public $public = true;
	public $exclude_from_search;
	public $publicly_queryable;
	public $show_ui;
	public $show_in_nav_menus;
	public $show_in_menu;
	public $show_in_admin_bar;
	public $menu_position;
	public $menu_icon;
	public $capability_type;
	public $capabilities;
	public $map_meta_cap;
	public $hierarchical;
	public $supports;
	public $register_meta_box_cb;
	public $taxonomies;
	public $has_archive;
	public $rewrite;
	public $permalink_epmask;
	public $query_var;
	public $can_export;
	public $delete_with_user;
	public $show_in_rest;
	public $rest_base;
	public $rest_controller_class;
	public $_builtin;
	public $_edit_link;

	public function to_array(): array {
		return (array) get_object_vars( $this );
	}
}
