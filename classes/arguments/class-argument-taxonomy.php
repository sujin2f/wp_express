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
	/*
	 * @var ?callable
	 */
	protected $meta_box_cb;
	protected function set_meta_box_cb( callable $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $show_admin_column;
	protected function set_show_admin_column( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $show_in_quick_edit;
	protected function set_show_in_quick_edit( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $show_tagcloud;
	protected function set_show_tagcloud( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?callable
	 */
	protected $update_count_callback;
	protected function set_update_count_callback( callable $value ): bool {
		return true;
	}
}
