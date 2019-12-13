<?php
/**
 * Assets Argement
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

class Argument_Assets extends Abstract_Argument {
	/*
	 * @var ?string[]
	 */
	protected $depends;

	/*
	 * @var ?bool
	 */
	protected $is_admin;

	/*
	 * @var ?bool
	 */
	protected $is_footer;

	/*
	 * @var ?array
	 */
	protected $translation;

	/*
	 * @var ?string
	 */
	protected $translation_key;

	/*
	 * @var Assets_Type
	 */
	protected $type;

	/*
	 * @var string
	 */
	protected $url;

	/*
	 * @var ?string
	 */
	protected $version;
}
