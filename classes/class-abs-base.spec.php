<?php
use Sujin\Wordpress\WP_Express\Abs_Base;
use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Exceptions\Initialized_Exception;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}
// @codeCoverageIgnoreEnd

class AbsBase_Inherited extends Abs_Base {}

class Unit_Test extends Test_Case {
	private $obj;

	public function setUp() {
		parent::setUp();
		$this->obj = AbsBase_Inherited::get_instance( 'Test' );
	}

	public function test_add_script() {
		$obj     = $this->obj->add_script( $this->get_stylesheet_directory_uri() . '/assets/dist/script.js' );
		$scripts = $this->get_private_property( $obj, 'scripts' );

		$this->assertContains( '/assets/dist/script.js', $scripts['wp-express-script-js']['url'] );
		$this->assertFalse( $scripts['wp-express-script-js']['is_admin'] );

		$obj     = $this->obj->add_script( $this->get_stylesheet_directory_uri() . '/assets/dist/another-script.js', true );
		$scripts = $this->get_private_property( $obj, 'scripts' );

		$this->assertTrue( $scripts['wp-express-another-script-js']['is_admin'] );

		// Enqueue Script
		$wp_scripts = wp_scripts();

		@$this->obj->_register_assets();  // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- Because of the filetime()
		$this->obj->_wp_enqueue_scripts();

		$this->assertEquals( $wp_scripts->queue[0], 'wp-express-script-js' );

		$this->obj->_admin_enqueue_scripts();

		$this->assertEquals( $wp_scripts->queue[1], 'wp-express-another-script-js' );
	}

	public function test_set_localize() {
		$obj = $this->obj->add_script( $this->get_stylesheet_directory_uri() . '/assets/dist/script.js' );
		$obj->script_localize( 'customVars', array( 'var1' => 'value1' ) );

		$scripts = $this->get_private_property( $obj, 'scripts' );

		$this->assertEquals( 'customVars', $scripts['wp-express-script-js']['translation-key'] );
		$this->assertEquals( 'value1', $scripts['wp-express-script-js']['translation']['var1'] );
	}

	public function test_add_style() {
		$obj    = $this->obj->add_style( $this->get_stylesheet_directory_uri() . '/assets/dist/style.css' );
		$styles = $this->get_private_property( $obj, 'styles' );

		$this->assertContains( '/assets/dist/style.css', $styles['wp-express-style-css']['url'] );
		$this->assertFalse( $styles['wp-express-style-css']['is_admin'] );

		$obj    = $this->obj->add_style( $this->get_stylesheet_directory_uri() . '/assets/dist/another-style.css', true );
		$styles = $this->get_private_property( $obj, 'styles' );

		$this->assertTrue( $styles['wp-express-another-style-css']['is_admin'] );

		// Enqueue Style
		$wp_styles = wp_styles();

		@$this->obj->_register_assets();  // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- Because of the filetime()
		$this->obj->_wp_enqueue_scripts();

		$this->assertEquals( $wp_styles->queue[0], 'wp-express-style-css' );

		$this->obj->_admin_enqueue_scripts();

		$this->assertEquals( $wp_styles->queue[1], 'wp-express-another-style-css' );
	}

	public function test_get_assets_handle() {
		$handle = $this->call_private_method( $this->obj, '_get_assets_handle', array( $this->get_stylesheet_directory_uri() . '/assets/dist/style.css' ) );
		$this->assertEquals( 'wp-express-style-css', $handle );
	}

	public function test_register_assets() {
		$obj = $this->obj->add_script( $this->get_stylesheet_directory_uri() . '/postcss.config.js' );
		$obj->script_localize( 'customVars', array( 'var1' => 'value1' ) );
		$obj = $this->obj->add_style( $this->get_stylesheet_directory_uri() . '/style.css' );

		@$this->obj->_register_assets(); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- Because of the filetime()

		$wp_scripts = wp_scripts();
		$wp_styles  = wp_styles();

		$this->assertEquals( '_WP_Dependency', get_class( $wp_scripts->registered['wp-express-postcss-config-js'] ) );
		$this->assertEquals( '_WP_Dependency', get_class( $wp_styles->registered['wp-express-style-css'] ) );
	}

	public function test_get_multiton_instance() {
		$instance1 = AbsBase_Inherited::get_instance( 'Test' );
		$instance2 = AbsBase_Inherited::get_instance( 'test 2' );
		$_instance = $this->get_private_property( $instance1, 'multiton_container' );

		$this->assertEquals( $instance1, $this->obj );
		$this->assertEquals( $instance2, AbsBase_Inherited::get_instance( 'test 2' ) );
		$this->assertEquals( 2, count( $_instance ) );
	}

	public function test_get_singleton_instance() {
		$instance  = AbsBase_Inherited::get_instance();
		$_instance = $this->get_private_property( $instance, 'singleton_container' );

		$this->assertEquals( $_instance, AbsBase_Inherited::get_instance() );
	}

	public function test_render_admin_message() {
		ob_start();
		$this->call_private_method( $this->obj, 'render_admin_message', array( 'Text' ) );
		$output = ob_get_clean();
		$this->assertEmpty( $output );

		define( 'WP_ADMIN', true );
		ob_start();
		$this->call_private_method( $this->obj, 'render_admin_message', array( 'Test Message' ) );
		$output = ob_get_clean();

		$this->assertContains( 'Test Message', $output );
	}

	public function test_get_id() {
		$actual = $this->obj->get_id();
		$this->assertEquals( 'test', $actual );

		// Exception
		$this->set_private_property( $this->obj, 'id', null );
		$actual = null;
		try {
			$actual = $this->obj->get_id();
		} catch ( Initialized_Exception $e ) {
			$actual = $e;
		}

		$this->assertTrue( $actual instanceof Initialized_Exception );
	}

	public function test_get_name() {
		$actual = $this->obj->get_name();
		$this->assertEquals( 'Test', $actual );

		// Exception
		$this->set_private_property( $this->obj, 'name', null );
		$actual = null;
		try {
			$actual = $this->obj->get_name();
		} catch ( Initialized_Exception $e ) {
			$actual = $e;
		}

		$this->assertTrue( $actual instanceof Initialized_Exception );
	}
}
