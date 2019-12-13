<?php
/**
 * Setting Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   3.0.0
 */

use Sujin\Wordpress\WP_Express\Setting;
// use Sujin\Wordpress\WP_Express\Fields\Settings\Input;
use Sujin\Wordpress\WP_Express\Admin;

class Setting_Test extends Test_Case {
	public function test_set_admin_page() {
		$admin_page = Admin::get_instance( 'Admin Test' );
		$settings   = Setting::get_instance( 'Setting Test' );
		$settings
			->admin_page( $admin_page )
			->register_setting();

		global $wp_settings_sections;

		$this->assertTrue( 
			array_key_exists( $admin_page->get_id(), $wp_settings_sections ),
			'😡 Admin page does not appear in the $wp_settings_sections.',
		);
		$this->assertTrue( 
			array_key_exists( $settings->get_id(), $wp_settings_sections[ $admin_page->get_id() ] ),
			'😡 Setting does not appear in the $wp_settings_sections.',
		);
	}

	// TODO
	// public function test_append() {
	// 	// $input      = Input::get_instance( 'Test Input' );
	// 	$admin_page = Admin::get_instance( 'Admin Test' );
	// 	$setting    = Setting::get_instance( 'Setting Test' );
	// 	$setting
	// 		->admin_page( $admin_page )
	// 		->append( $input );

	// 	$_setting = $this->get_private_property( $input, 'setting' );

	// 	$this->assertEquals( $admin_page, $setting->admin_page() );
	// 	$this->assertEquals( $setting, $_setting );
	// 	$this->assertTrue( true );
	// }
}
