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
	protected function __construct( string $name, array $attrs = array() ) {
		parent::__construct( $name, $attrs );
		add_action( 'admin_init', array( $this, '_add_settings_field' ) );
	}

	public function get( ?int $_ = null ) {
		return get_option( $this->get_id() );
	}

	private $cb_attach_to;
	public function attach_to( Setting $setting ) {
		$this->cb_attach_to = $setting;
		remove_all_filters( $this->_get_filter_key() );
		add_filter( $this->_get_filter_key(), array( $this, '_callback_attach_to' ) );
		return $this;
	}

	public function _callback_attach_to() {
		return $this->cb_attach_to;
	}

	public function _add_settings_field() {
		$setting_section = apply_filters( $this->_get_filter_key(), null );

		if ( ! ( $setting_section instanceof Setting ) ) {
			return;
		}

		$admin_page = apply_filters( $setting_section->_get_filter_key(), 'general' );

		add_settings_field(
			$this->get_id(),
			$this->get_name(),
			array( $this, '_render' ),
			$admin_page,
			$setting_section->get_id()
		);

		register_setting( $admin_page, $this->get_id() );
	}

	protected function _refresh_attributes( ?int $_ = null ) {
		if ( empty( $this->_attributes['value'] ) ) {
			$this->_attributes['value'] = get_option( $this->get_id() );
		}
	}

	protected function _render_wrapper_open() {}

	protected function _render_wrapper_close() {}

	public function _get_filter_key() {
		return self::PREFIX . '_' . $this->get_id() . '_setting_field';
	}
}
