<?php
/**
 * Options for Post Type
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 * @todo    Data types
 */

namespace Sujin\Wordpress\WP_Express\Types;

class Post_Type_Argument extends Abstract_WP_Object_Argument {
	private $_edit_link;
	private $can_export;
	private $capability_type;
	private $delete_with_user;
	private $exclude_from_search;
	private $has_archive;
	private $map_meta_cap;
	private $menu_icon;
	private $menu_position;
	private $permalink_epmask;
	private $register_meta_box_cb;
	private $show_in_admin_bar;
	private $supports;
	private $taxonomies;
}
