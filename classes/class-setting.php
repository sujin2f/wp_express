<?php
/**
 * Admin Class
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 * @todo    Attach to the existing page
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Abs_Base;
use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Fields\Abs_Setting_Element;
use Sujin\Wordpress\WP_Express\Enum\Options_Setting;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Setting extends Abs_Base {
	protected function __construct( string $name ) {
		parent::__construct( $name );

		$this->allowed_option = new Options_Setting();
		add_action( 'admin_init', array( $this, '_register_setting' ) );
	}

	public function add( Abs_Setting_Element $field ): Setting {
		remove_all_filters( $field->_get_filter_key() );

		add_filter(
			$field->_get_filter_key(),
			function() {
				return $this;
			}
		);

		return $this;
	}

	private $cb_attach_to;
	public function attach_to( $admin_page ): Setting {
		$this->cb_attach_to = $admin_page;

		remove_all_filters( $this->_get_filter_key() );
		add_filter( $this->_get_filter_key(), array( $this, '_callback_attach_to' ) );

		return $this;
	}

	public function _callback_attach_to() {
		if ( $this->cb_attach_to instanceof Admin ) {
			return $this->cb_attach_to->get_id();
		}

		return $this->cb_attach_to;
	}

	public function _register_setting() {
		$admin_page = apply_filters( $this->_get_filter_key(), 'general' );
		add_settings_section( $this->get_id(), $this->get_name(), null, $admin_page );
	}

	public function _get_filter_key(): string {
		return self::PREFIX . '_' . $this->get_id() . '_admin_page';
	}
}
