<?php
/**
 * Admin Class
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @param   string $name The name of the componenet
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Arguments\Argument_Setting;
use Sujin\Wordpress\WP_Express\Fields\Abstract_Filed_Setting;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;
use Sujin\Wordpress\WP_Express\Helpers\Trait_With_Argument;

class Setting extends Abstract_Component {
	use Trait_Multiton;
	use Trait_With_Argument;

	protected function __construct( string $name ) {
		parent::__construct( $name );
		$this->argument = new Argument_Setting();
		add_action( 'admin_init', array( $this, 'register_setting' ) );
	}

	public function get_admin_page() {
		return $this->argument->get( 'admin_page' );
	}

	public function append( Abstract_Filed_Setting $field ): self {
		$field->append_to( $this );
		return $this;
	}

	public function append_to( $admin ): self {
		$this->argument->set( 'admin_page', $admin );
		return $this;
	}

	public function register_setting(): void {
		$admin_page = ( $this->argument->get( 'admin_page' ) instanceof Admin ) 
			? $this->argument->get( 'admin_page' )->get_id() 
			: $this->argument->get( 'admin_page' );
		add_settings_section( $this->get_id(), $this->get_name(), null, $admin_page );
	}
}
