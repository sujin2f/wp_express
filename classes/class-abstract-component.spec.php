<?php
/**
 * Component Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   3.0.0
 */

use Sujin\Wordpress\WP_Express\Abstract_Component;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;

class Component_Inherited extends Abstract_Component {
	use Trait_Multiton;
}

class Component_Test extends Test_Case {
	/*
	 * Test render_admin_message()
	 */
	public function test_render_admin_message(): void {
		$component = Component_Inherited::get_instance( 'Test' );

		// Part 1: Front page
		$this->go_to( '/' );
		ob_start();
		$this->call_private_method(
			$component,
			'render_admin_message',
			array( 'Text' ),
		);
		$actual = ob_get_clean();

		// @sssertion Empty becuase this is not admin screen
		$this->assertEmpty(
			$actual,
			'😡 Front page should not show the admin message.',
		);

		// Part 2: Go to admin
		set_current_screen( 'edit-post' );
		ob_start();
		$this->call_private_method(
			$component,
			'render_admin_message',
			array( 'Test Message' ),
		);
		$actual = ob_get_clean();

		// @sssertion
		$this->assertContains(
			'Test Message',
			$actual,
			'😡 Admin page should show the admin message.',
		);
	}

	/*
	 * Test get_id()
	 */
	public function test_get_id(): void {
		// @sssertion
		$this->assertEquals(
			'test',
			Component_Inherited::get_instance( 'Test' )->get_id(),
			'😡 Component id is not matched as it expected.',
		);
	}

	/*
	 * Test get_name()
	 */
	public function test_get_name(): void {
		// @sssertion
		$this->assertEquals(
			'Test',
			Component_Inherited::get_instance( 'Test' )->get_name(),
			'😡 Component id is not matched as it expected.',
		);
	}
}
