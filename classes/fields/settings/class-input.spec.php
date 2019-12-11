<?php
use Sujin\Wordpress\WP_Express\Fields\Settings\Input;

class Settings_Input_Test extends Test_Case {
	public function test_render() {
		$field = Input::get_instance( 'Field 1' );
		ob_start();
		$field->render();
		$output = ob_get_clean();
		$this->assertContains( 'field-1', $output );
	}
}
