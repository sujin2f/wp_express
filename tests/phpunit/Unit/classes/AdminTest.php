<?php
namespace Sujin\Wordpress\WP_Express\Tests\Unit;

use Sujin\Wordpress\WP_Express\Admin;
use Exception;

class AdminTest extends TestCase {
	public function set_root_position_provider() {
		return array(
			'String, New Position' => array( 'Admin Page Test Case', null, 100 ),
			'Number, New Position' => array( 'Admin Page 300', 300, 300 ),
		);
	}

	/**
	 * @dataProvider set_root_position_provider
	 *
	 * @param string     $page_name         Adamin Page Name
	 * @param string|int $position          Input Position
	 * @param int        $expected_position Expected Menu Position
	 */
	public function test_set_root_position( string $page_name, $position, int $expected_position ) {
		wp_set_current_user( 1 );
		global $menu;
		try {
			include_once( self::$home_dir . '/wordpress/wp-admin/menu.php' );
		} catch ( Exception $_ ) {
		}

		$admin_page = Admin::get_instance( $page_name )->position( $position );
		$admin_page->_register_admin_menu();

		$expected = $menu[ $expected_position ][0];
		$this->assertEquals( $page_name, $expected );
	}

	public function set_child_position_provider() {
		return array(
			'Under WP Menu'          => array( 'Child Tools', 'tools', 'tools.php' ),
			'Under WP Menu, by Name' => array( 'Child Post', 'Posts', 'edit.php' ),
			'Under Express Menu'     => array( 'Child 300', 'Admin Page 300', 'admin-page-300', true ),
		);
	}

	/**
	 * @dataProvider set_child_position_provider
	 *
	 * @param string       $page_name         Adamin Page Name
	 * @param string|Admin $position          Input Position
	 * @param string       $expected_position Expected Menu Position
	 * @param ?boolean     $is_express_class  Position is EX class
	 */
	public function test_set_child_position( string $page_name, $position, string $expected_position, ?bool $is_express_class = null ) {
		wp_set_current_user( 1 );
		global $submenu;
		try {
			include_once( self::$home_dir . '/wordpress/wp-admin/menu.php' );
		} catch ( Exception $_ ) {
		}

		$admin_page = Admin::get_instance( $page_name )->position( $position );
		if ( true === $is_express_class ) {
			$admin_page->position( Admin::get_instance( $position ) );
		}
		$admin_page->_register_admin_menu();

		$expected = $submenu[ $expected_position ];
		$expected = array_pop( $expected )[0];

		$this->assertEquals( $page_name, $expected );
	}

	public function test_get_menu_args() {
		$menu_name = 'Admin Page Test Case';
		$args      = $this->call_private_method(
			Admin::get_instance( $menu_name ),
			'_get_menu_args'
		);

		$this->assertEquals( count( $args ), 6 );
		$this->assertEquals( $args[0], $menu_name );
		$this->assertEquals( $args[1], $menu_name );
		$this->assertEquals( $args[2], 'manage_options' );
		$this->assertEquals( $args[3], Admin::get_instance( $menu_name )->get_id() );
		$this->assertEquals( get_class( $args[4][0] ), 'Sujin\Wordpress\WP_Express\Admin' );
		$this->assertEquals( $args[4][1], '_render' );
		$this->assertEquals( $args[5], Admin::get_instance( $menu_name )->icon() );
	}
}
