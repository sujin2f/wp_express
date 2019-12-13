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
	protected $_builtin;
	protected $capabilities;
	protected $description;
	protected $hierarchical;
	protected $label;
	protected $labels;
	protected $private = true;
	protected $privately_queryable;
	protected $query_var;
	protected $rest_base;
	protected $rest_controller_class;
	protected $rewrite;
	protected $show_in_menu;
	protected $show_in_nav_menus;
	protected $show_in_rest;
	protected $show_ui;
}
