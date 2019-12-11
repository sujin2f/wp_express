<?php
/**
 * Admin Class
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @param   ?string $name The name of the componenet
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Component;
use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Fields\Setting_Component;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;

class Setting extends Component {
	use Trait_Multiton;

	public const ADMIN_PAGE = 'admin_page';

	private $admin_page = 'general';

	protected function __construct( ?string $name ) {
		parent::__construct( $name );
		add_action( 'admin_init', array( $this, 'register_setting' ) );
	}

	public function __call( string $name, array $arguments ) {
		switch ( strtolower( $name ) ) {
			case self::ADMIN_PAGE:
				if ( empty( $arguments ) ) {
					return $this->{$name};
				}

				$this->{$name} = $arguments[0];
				break;
		}

		return $this;
	}

	public function append( Setting_Component $field ): Setting {
		$field->append_to( $this );
		return $this;
	}

	public function append_to( $admin ): Setting {
		$this->admin_page = $admin;
		return $this;
	}

	public function register_setting() {
		$admin_page = ( $this->admin_page instanceof Admin ) ? $this->admin_page->get_id() : $this->admin_page;
		add_settings_section( $this->get_id(), $this->get_name(), null, $admin_page );
	}
}
