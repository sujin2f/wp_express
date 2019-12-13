<?php
/**
 * Options for Taxonomy
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 * @todo    Data types
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

class Argument_Taxonomy extends Abstract_WP_Object_Argument {
	protected $meta_box_cb;
	protected $show_admin_column;
	protected $show_in_quick_edit;
	protected $show_tagcloud;
	protected $sort;
	protected $update_count_callback;
}
