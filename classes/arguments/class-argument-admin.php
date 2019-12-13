<?php
/**
 * Admin Argement
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

class Argument_Admin extends Abstract_Argument {
	/*
	 * @var string|Admin
	 */
	protected $position = 'settings';

	/*
	 * @var string
	 */
	protected $icon = 'dashicons-admin-generic';

	/*
	 * @var string
	 */
	protected $capability = 'manage_options';

	/*
	 * @var string
	 */
	protected $plugin;
}
