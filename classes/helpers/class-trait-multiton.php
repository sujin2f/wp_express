<?php
/**
 * Object which has id and name
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers;

interface Interface_Identifier {
	/**
	 * Get Unique Identifier
	 *
	 * @return string
	 */
	public function get_id(): string;

	/**
	 * Get object name
	 *
	 * @return string
	 */
	public function get_name(): string;
}
