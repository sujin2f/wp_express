<?php
namespace Sujin\Wordpress\WP_Express\Tests\Unit\Fields\Settings;

use Sujin\Wordpress\WP_Express\Tests\Unit\TestCase;
use Sujin\Wordpress\WP_Express\Fields\Settings\Input;

class InputTest extends TestCase {
	public function test_render() {
		$field = Input::get_instance( 'Field 1' );
		ob_start();
		$field->render();
		$output = ob_get_clean();
		$this->assertContains( 'field-1', $output );
	}
}
