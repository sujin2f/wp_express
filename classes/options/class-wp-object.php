<?php
/**
 * Options for post type
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Options;

abstract class Wp_Object {
	public $_builtin;
	public $capabilities;
	public $description;
	public $hierarchical;
	public $label;
	public $labels;
	public $public = true;
	public $publicly_queryable;
	public $query_var;
	public $rest_base;
	public $rest_controller_class;
	public $rewrite;
	public $show_in_menu;
	public $show_in_nav_menus;
	public $show_in_rest;
	public $show_ui;

	public function to_array(): array {
		return (array) get_object_vars( $this );
	}
}
