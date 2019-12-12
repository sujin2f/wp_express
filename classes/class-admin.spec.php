<?php
/**
 * Creates Admin Page
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   3.0.0
 */

use Sujin\Wordpress\WP_Express\Admin;
// use Sujin\Wordpress\WP_Express\Setting;
// use Sujin\Wordpress\WP_Express\Fields\Settings\Input;
// use Sujin\Wordpress\WP_Express\Fields\Settings\Textarea;
// use Sujin\Wordpress\WP_Express\Fields\Settings\Editor;
// use Sujin\Wordpress\WP_Express\Fields\Settings\Attachment;
// use Sujin\Wordpress\WP_Express\Fields\Settings\Checkbox;
// use Sujin\Wordpress\WP_Express\Fields\Settings\Radio;
// use Sujin\Wordpress\WP_Express\Fields\Settings\Select;

class Admin_Test extends Test_Case {
	public function root_position_provider() {
		return array(
			'String, New Position' => array( 
				'page_name' => 'Admin Page Test Case', 
				'position'  => null, 
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
		} catch ( Exception $_ ) {}

		Admin::get_instance( $page_name )
			->position( $position )
			->register_admin_menu()
			;

		$this->assertEquals( 
			$page_name, 
			$menu[ $expected ][0],
			'😡 Menu registration failed.',
		);
	}

	public function child_position_provider() {
		return array(
			'Under WP Menu'          => array( 
				'page_name' => 'Child Tools', 
				'position'  => 'tools', 
				'expected'  => 'tools.php',
			),
			'Under WP Menu, by Name' => array( 
				'page_name' => 'Child Post', 
				'position'  => 'Posts', 
				'expected'  => 'edit.php',
			),
			'Under Express Menu'     => array( 
				'page_name' => 'Child 300', 
				'position'  => 'Admin Page 300',
				'expected'  => 'admin-page-300', 
				'is_class'  => true,
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
	public function test_child_position( string $page_name, string $position, string $expected, bool $is_class = false ): void {
		wp_set_current_user( 1 );
		global $submenu;
		try {
			include_once( self::$home_dir . '/wordpress/wp-admin/menu.php' );
		} catch ( Exception $_ ) {}

		$admin_page = ( true === $is_class )
			? Admin::get_instance( $page_name )->position( Admin::get_instance( $position ) )
			: Admin::get_instance( $page_name )->position( $position );
		$admin_page->register_admin_menu();

		$expected = $submenu[ $expected ];
		$expected = array_pop( $expected )[0];

		$this->assertEquals( 
			$page_name, 
			$expected,
			'😡 Sub-menu registration failed.',
		);
	}

	public function test_get_menu_args(): void {
		$menu_name = 'Admin Page Test Case';
		$args      = $this->call_private_method(
			Admin::get_instance( $menu_name ),
			'get_menu_args'
		);

		$this->assertEquals( $args[0], $menu_name );
		$this->assertEquals( $args[1], $menu_name );
		$this->assertEquals( $args[2], 'manage_options' );
		$this->assertEquals( $args[3], Admin::get_instance( $menu_name )->get_id() );
		$this->assertEquals( get_class( $args[4][0] ), 'Sujin\Wordpress\WP_Express\Admin' );
		$this->assertEquals( $args[4][1], 'render' );
	}

	// public function test_render() {
	// 	$admin      = Admin::get_instance( 'Admin Page Test Case' );
	// 	$setting    = Setting::get_instance( 'Test Setting' );
	// 	$input      = Input::get_instance( 'Input' );
	// 	$textarea   = Textarea::get_instance( 'Textarea' );
	// 	$editor     = Editor::get_instance( 'Editor' );
	// 	$attachment = Attachment::get_instance( 'Attachment' );
	// 	$checkbox   = Checkbox::get_instance( 'Checkbox' );
	// 	$radio      = Radio::get_instance( 'Radio' )
	// 		->options( array( 'Radio 1', 'Radio 2' ) );
	// 	$select     = Select::get_instance( 'Select' )
	// 		->options( array( 'Select 1', 'Select 2' ) );

	// 	$admin->append( $setting );
	// 	$setting
	// 		->append( $input )
	// 		->append( $textarea )
	// 		->append( $editor )
	// 		->append( $attachment )
	// 		->append( $checkbox )
	// 		->append( $radio )
	// 		->append( $select );

	// 	$setting->register_setting();
	// 	$input->add_settings_field();
	// 	$textarea->add_settings_field();
	// 	$editor->add_settings_field();
	// 	$attachment->add_settings_field();
	// 	$checkbox->add_settings_field();
	// 	$radio->add_settings_field();
	// 	$select->add_settings_field();

	// 	ob_start();
	// 	$admin->render();
	// 	$actual = ob_get_clean();

	// 	$this->assertContains( 'id="wp-express-admin-admin-page-test-case"', $actual );
	// 	$this->assertContains( '<h2>Test Setting</h2>', $actual );
	// 	$this->assertContains( 'name="input[0]"', $actual );
	// 	$this->assertContains( 'id="wp-express__field__textarea__textarea"', $actual );
	// 	$this->assertContains( 'id="wp-editor-editor-container"', $actual );
	// 	$this->assertContains( '<th scope="row">Attachment</th>', $actual );
	// 	$this->assertContains( 'name="checkbox[0]"', $actual );
	// 	$this->assertContains( '<label for="wp-express__field__radio__radio__radio-1">', $actual );
	// 	$this->assertContains( '<label for="wp-express__field__radio__radio__radio-2">', $actual );
	// 	$this->assertContains( 'id="wp-express__field__select__select"', $actual );
	// 	$this->assertContains( '<option value="Select 1">Select 1</option>', $actual );
	// }
}
