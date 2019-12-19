<?php
/**
 * Creates Admin Page
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   3.0.0
 */

use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Settings_Section;
use Sujin\Wordpress\WP_Express\Post_Type;

class Admin_Test extends Test_Case {
	public function root_position_provider(): array {
		return array(
			'String, New Position' => array(
				'page_name' => 'Admin Page 100',
				'position'  => 100,
				'expected'  => 100,
			),
			'Number, New Position' => array(
				'page_name' => 'Admin Page 300',
				'position'  => 300,
				'expected'  => 300,
			),
		);
	}

	/**
	 * @dataProvider root_position_provider
	 *
	 * @param string $page_name Adamin Page Name
	 * @param ?int   $position  Input Position
	 * @param int    $expected  Expected Menu Position
	 */
	public function test_root_position( string $page_name, ?int $position, int $expected ): void {
		wp_set_current_user( 1 );
		global $menu;
		try {
			include_once( self::$home_dir . '/wordpress/wp-admin/menu.php' );
		} catch ( Exception $_ ) {
		}

		$admin = Admin::get_instance( $page_name );

		if ( $position ) {
			$admin->position( $position );
		}

		$admin->register_menu();
		$this->assertEquals(
			$page_name,
			$menu[ $expected ][0],
			'😡 Menu registration failed.',
		);
	}

	public function child_position_provider(): array {
		return array(
			'Under WP Menu'                   => array(
				'page_name' => 'Child Tools',
				'position'  => 'tools',
				'expected'  => 'tools.php',
			),
			'Under WP Menu, by Name'          => array(
				'page_name' => 'Child Post 1',
				'position'  => 'Posts',
				'expected'  => 'edit.php',
			),
			'Under WP Menu, by ID'            => array(
				'page_name' => 'Child Menu 2',
				'position'  => 10,
				'expected'  => 'upload.php',
			),
			'Under Express Menu'              => array(
				'page_name' => 'Child 300',
				'position'  => Admin::get_instance( 'Admin Page 300' ),
				'expected'  => 'admin-page-300',
			),
			'Under Express Post Type'         => array(
				'page_name' => 'Child Menu 3',
				'position'  => Post_Type::get_instance( 'Custom Post' ),
				'expected'  => 'custom-post',
			),
			'Under Express Post Type, String' => array(
				'page_name' => 'Child Menu 4',
				'position'  => 'Admin Page 300',
				'expected'  => 'admin-page-300',
			),
		);
	}

	/**
	 * @dataProvider child_position_provider
	 *
	 * @param string $page_name Adamin Page Name
	 * @param string $position  Input Position
	 * @param string $expected  Expected Menu Position
	 * @param ?bool  $is_class  Position is EX class
	 */
	public function test_child_position( string $page_name, $position, string $expected ): void {
		wp_set_current_user( 1 );
		global $submenu;
		try {
			include_once( self::$home_dir . '/wordpress/wp-admin/menu.php' );
		} catch ( Exception $_ ) {
		}

		$admin_page = Admin::get_instance( $page_name )->position( $position );
		$admin_page->register_menu();

		$expected = $submenu[ $expected ];
		$expected = array_pop( $expected )[0];

		$this->assertEquals(
			$expected,
			$page_name,
			'😡 Sub-menu registration failed.',
		);
	}

	public function test_append(): void {
		$admin   = Admin::get_instance( 'Admin Test' );
		$setting = Settings_Section::get_instance( 'Setting Test' );

		$admin->append( $setting );

		$argument = $this->get_private_property( $setting, 'argument' );
		$actual   = $argument->get( 'admin_page' )->get_name();

		$this->assertEquals(
			'Admin Test',
			$actual,
			'😡 Appending Seting to Admin has failed.',
		);
	}

	public function test_plugin_action_links(): void {
		$actual = Admin::get_instance( 'Admin Test' )
			->plugin( 'akismet' )
			->plugin_action_links(
				array(),
				'',
				array( 'Name' => 'akismet' ),
			);

		$this->assertEquals(
			'<a href=""><span class="dashicons-before dashicons-admin-generic"></span> Setting</a>',
			$actual['setting'],
			'😡 plugin_action_links() has failed.',
		);
	}

	public function test_render(): void {
		ob_start();
		Admin::get_instance( 'Admin Test' )->render();
		$actual = ob_get_clean();

		$this->assertContains(
			'class="wp-express admin wrap"',
			$actual,
			'😡 Admin render does not contain expected string.',
		);

		$this->assertContains(
			'<span class="dashicons dashicons-admin-generic"></span>',
			$actual,
			'😡 Admin render does not contain expected string.',
		);

		$this->assertContains(
			'<form method="post" action="options.php">',
			$actual,
			'😡 Admin render does not contain expected string.',
		);

		$this->assertContains(
			'<input type=\'hidden\' name=\'option_page\' value=\'admin-test\' /><input type="hidden" name="action" value="update" /><input type="hidden" id="_wpnonce" name="_wpnonce" value=',
			$actual,
			'😡 Admin render does not contain expected string.',
		);

		$this->assertContains(
			'<input type="hidden" name="_wp_http_referer"',
			$actual,
			'😡 Admin render does not contain expected string.',
		);

		$this->assertContains(
			'<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  /></p>',
			$actual,
			'😡 Admin render does not contain expected string.',
		);
	}

	public function test_get_menu_args(): void {
		$menu_name = 'Admin Page Test Case';
		$args      = $this->call_private_method(
			Admin::get_instance( $menu_name ),
			'get_menu_args'
		);

		$this->assertEquals(
			$menu_name,
			$args[0],
			'😡 Menu argument 1 is not matching.',
		);
		$this->assertEquals(
			$menu_name,
			$args[1],
			'😡 Menu argument 2 is not matching.',
		);
		$this->assertEquals(
			'manage_options',
			$args[2],
			'😡 Menu argument 3 is not matching.',
		);
		$this->assertEquals(
			Admin::get_instance( $menu_name )->get_id(),
			$args[3],
			'😡 Menu argument 4 is not matching.',
		);
		$this->assertEquals(
			'Sujin\Wordpress\WP_Express\Admin',
			get_class( $args[4][0] ),
			'😡 Menu argument 5.1 is not matching.',
		);
		$this->assertEquals(
			'render',
			$args[4][1],
			'😡 Menu argument 5.2 is not matching.',
		);
	}
}
