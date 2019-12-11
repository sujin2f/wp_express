<?php
/**
 * Options for post type
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Types;

class Post_Type_Argument extends Wp_Object {
	public $_edit_link;
	public $can_export;
	public $capability_type;
	public $delete_with_user;
	public $exclude_from_search;
	public $has_archive;
	public $map_meta_cap;
	public $menu_icon;
	public $menu_position;
	public $permalink_epmask;
	public $register_meta_box_cb;
	public $show_in_admin_bar;
	public $supports;
	public $taxonomies;
}
