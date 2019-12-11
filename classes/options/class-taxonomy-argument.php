<?php
/**
 * Options for post type
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Options;

class Taxonomy_Argument extends Wp_Object {
	public $meta_box_cb;
	public $show_admin_column;
	public $show_in_quick_edit;
	public $show_tagcloud;
	public $sort;
	public $update_count_callback;
}
