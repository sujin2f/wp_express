<?php
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Input;

class Post_Meta_Input_Test extends Test_Case {
	public function test_render() {
		$field = Input::get_instance( 'Field 1' );

		ob_start();
		$field->render_form();
		$output = ob_get_clean();
		$this->assertContains( 'field-1', $output );
	}

	public function test_update_and_get() {
		global $post;

		$post    = $this->factory->post->create_and_get();
		$post_id = $post->ID;

		$field = Input::get_instance( 'Field 1' );
		$field->update( $post_id, 'Value 1' );

		$this->assertEquals( 'Value 1', $field->get() );
	}
}
