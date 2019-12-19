<?php
/**
 * Post meta -- input Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Input;

class Post_Meta_Input_Test extends Test_Case {
	public function test_render(): void {
		$field = Input::get_instance( 'Field 1' );
		ob_start();
		$field->render_form();
		$output = ob_get_clean();
		$this->assertContains(
			'field-1',
			$output,
			'😡 Input render has failed.',
		);
	}

	public function test_update_and_get(): void {
		global $post;

		$post    = $this->factory->post->create_and_get();
		$post_id = $post->ID;

		$field = Input::get_instance( 'Field 1' );
		$field->update( $post_id, 'Value 1' );

		$this->assertEquals(
			'Value 1',
			$field->get(),
			'😡 Input value has not been saved.',
		);
	}

	public function test_single_and_multiple(): void {
		global $post;

		$post    = $this->factory->post->create_and_get();
		$post_id = $post->ID;

		$field = Input::get_instance( 'Field 2' )
			->single( false );
		$field->update( $post_id, array( 'Value 1' ) );
		$this->assertEquals(
			array( 'Value 1' ),
			$field->get(),
			'😡 Multiple value has not been supported.',
		);
	}

	public function test_get_data_type(): void {
		$field = Input::get_instance( 'Field 2' );
		$field->type( 'string' );
		$actual = $this->call_private_method( $field, 'get_data_type' );
		$this->assertEquals(
			'string',
			$actual,
			'😡 Input data type assignment has failed.',
		);
	}
}
