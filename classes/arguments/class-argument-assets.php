<?php
/**
 * Assets Argement
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Arguments;

use Sujin\Wordpress\WP_Express\Helpers\Enums\Assets_Type;

class Argument_Assets extends Abstract_Argument {
	/*
	 * @var ?string[]
	 */
	protected $depends;
	protected function set_depends( array $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $is_admin;
	protected function set_is_admin( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?bool
	 */
	protected $is_footer;
	protected function set_is_footer( bool $value ): bool {
		return true;
	}

	/*
	 * @var ?array
	 */
	protected $translation;
	protected function set_translation( array $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $translation_key;
	protected function set_translation_key( string $value ): bool {
		return true;
	}

	/*
	 * @var Assets_Type
	 */
	protected $type;
	protected function set_type( Assets_Type $value ): bool {
		return true;
	}

	/*
	 * @var string
	 */
	protected $url;
	protected function set_url( string $value ): bool {
		return true;
	}

	/*
	 * @var ?string
	 */
	protected $version;
	protected function set_version( string $value ): bool {
		return true;
	}
}
