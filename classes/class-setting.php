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

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Setting extends Abs_Base {
	public const ADMIN_PAGE = 'admin_page';

	// Single/Multiton container
	protected static $multiton_container  = array();
	protected static $singleton_container = null;

	private $admin_page = 'general';

	protected function __construct( string $name ) {
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

	public function add( Abs_Setting_Element $field ): Setting {
		$field->attach_to( $this );
		return $this;
	}

	public function register_setting() {
		$admin_page = ( $this->admin_page instanceof Admin ) ? $this->admin_page->get_id() : $this->admin_page;
		add_settings_section( $this->get_id(), $this->get_name(), null, $admin_page );
	}
}
