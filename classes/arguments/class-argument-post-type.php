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
	protected $_edit_link;
	protected $can_export;
	protected $capability_type;
	protected $delete_with_user;
	protected $exclude_from_search;
	protected $has_archive;
	protected $map_meta_cap;
	protected $menu_icon;
	protected $menu_position;
	protected $permalink_epmask;
	protected $register_meta_box_cb;
	protected $show_in_admin_bar;
	protected $supports = array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions' );
	protected $taxonomies;
}
