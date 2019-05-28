<?php
namespace Sujin\Wordpress\WP_Express\Tests\Unit;

use Sujin\Wordpress\WP_Express\Tests\Unit\TestCase;
use Sujin\Wordpress\WP_Express\Setting;
use Sujin\Wordpress\WP_Express\Fields\Settings\Input;
use Sujin\Wordpress\WP_Express\Admin;

class SettingTest extends TestCase {
	public function test_set_admin_page() {
		$admin_page = Admin::get_instance( 'Admin Test' );
		$settings   = Setting::get_instance( 'Setting Test' );
		$settings
			->admin_page( $admin_page )
			->_register_setting();

		global $wp_settings_sections;

		$this->assertTrue( array_key_exists( $admin_page->get_id(), $wp_settings_sections ) );
		$this->assertTrue( array_key_exists( $settings->get_id(), $wp_settings_sections[ $admin_page->get_id() ] ) );
	}

	// TODO
	public function test_add() {
		$input      = Input::get_instance( 'Test Input' );
		$admin_page = Admin::get_instance( 'Admin Test' );
		$setting    = Setting::get_instance( 'Setting Test' );
		$setting
			->admin_page( $admin_page )
			->add( $input );

		$_setting = $this->get_private_property( $input, '_setting' );

		$this->assertEquals( $admin_page, $setting->admin_page() );
		$this->assertEquals( $setting, $_setting );
		$this->assertTrue( true );
	}
}
