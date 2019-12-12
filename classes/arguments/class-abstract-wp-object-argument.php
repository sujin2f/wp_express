<?php
/**
 * Options for post type
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 * @todo    Data types
 */

namespace Sujin\Wordpress\WP_Express\Types;

abstract class Abstract_WP_Object_Argument extends Abstract_Arguments {
	private $_builtin;
	private $capabilities;
	private $description;
	private $hierarchical;
	private $label;
	private $labels;
	private $private = true;
	private $privately_queryable;
	private $query_var;
	private $rest_base;
	private $rest_controller_class;
	private $rewrite;
	private $show_in_menu;
	private $show_in_nav_menus;
	private $show_in_rest;
	private $show_ui;
}
