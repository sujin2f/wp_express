<?php
/**
 * Admin Argement
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Types;

class Admin_Argument extends Abstract_Arguments {
    /*
     * @var string|Admin
     */
	private $position   = 'settings';

    /*
     * @var string
     */
	private $icon       = 'dashicons-admin-generic';

    /*
     * @var string
     */
	private $capability = 'manage_options';

    /*
     * @var string
     */
	private $plugin;
}
