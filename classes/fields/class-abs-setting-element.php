<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Setting;
use Sujin\Wordpress\WP_Express\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

abstract class Abs_Setting_Element extends Abs_Base_Element {
	private $setting;

	protected function __construct( string $name, array $attrs = array() ) {
		parent::__construct( $name, $attrs );
		add_action( 'admin_init', array( $this, 'add_settings_field' ) );
	}

	public function get( ?int $_ = null ) {
		return get_option( $this->get_id() );
	}

	public function add_settings_field() {
		if ( empty( $this->setting ) || empty( $this->setting->admin_page() ) ) {
			return;
		}

		$parent_id =
			( $this->setting->admin_page() instanceof Admin )
			? $this->setting->admin_page()->get_id()
			: $this->setting->admin_page();

		add_settings_field(
			$this->get_id(),
			$this->get_name(),
			array( $this, 'render' ),
			$parent_id,
			$this->setting->get_id()
		);

		register_setting( $parent_id, $this->get_id() );
	}

	public function attach_to( Setting $setting ) {
		$this->setting           = $setting;
		$this->options['legend'] = $setting->get_name();
	}

	protected function refresh_value( ?int $_ = null ) {
		if ( empty( $this->attributes['value'] ) ) {
			$this->attributes['value'] = get_option( $this->get_id() );
		}
	}

	protected function render_wrapper_open() {}

	protected function render_wrapper_close() {}
}
