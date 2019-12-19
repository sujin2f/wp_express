<?php
/**
 * Settings_Section Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   3.0.0
 */

use Sujin\Wordpress\WP_Express\Settings_Section;
use Sujin\Wordpress\WP_Express\Admin;

class Settings_Section_Test extends Test_Case {
	public function test_set_admin_page() {
		$admin_page = Admin::get_instance( 'Admin Test' );
		$settings   = Settings_Section::get_instance( 'Setting Test' );
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
			'😡 Settings_Section does not appear in the $wp_settings_sections.',
		);
	}

	public function test_get_admin_page() {
		$admin_page = Admin::get_instance( 'Admin Test' );
		$settings   = Settings_Section::get_instance( 'Setting Test' );
		$settings
			->admin_page( $admin_page )
			->register_setting();

		$actual = $settings->get_admin_page();
		$this->assertEquals(
			$admin_page,
			$actual,
			'😡 Settings_Section::append() does not work.',
		);
	}
}
