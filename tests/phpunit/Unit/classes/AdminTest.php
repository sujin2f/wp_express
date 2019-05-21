<?php
namespace Sujin\Wordpress\WP_Express\Tests\Unit;

use Sujin\Wordpress\WP_Express\Admin;

class AdminTest extends TestCase {
	public function test_set_position() {
		wp_set_current_user( 1 );

		$admin_page = Admin::get_instance( 'Admin Page' );
		$admin_page
			->position( 'option' )
			->_admin_menu();

		$actual   = $this->get_private_property( $admin_page, '_admin_url' );
		$expected = 'http://example.org/wp-admin/options-general.php?page=admin-page';
		$this->assertEquals( $actual, $expected );

		$actual   = $this->get_private_property( $admin_page, '_position' );
		$expected = 'option';
		$this->assertEquals( $actual, $expected );
	}
}
