<?php
/**
 * Options for Taxonomy
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 * @todo    Data types
 */

namespace Sujin\Wordpress\WP_Express\Types;

class Taxonomy_Argument extends Abstract_WP_Object_Argument {
	private $meta_box_cb;
	private $show_admin_column;
	private $show_in_quick_edit;
	private $show_tagcloud;
	private $sort;
	private $update_count_callback;
}
