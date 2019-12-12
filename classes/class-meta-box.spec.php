<?php
use Sujin\Wordpress\WP_Express\Meta_Box;
use Sujin\Wordpress\WP_Express\Post_Type;
// use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Input;
// use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Textarea;
// use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Editor;
// use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Attachment;
// use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Checkbox;
// use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Radio;
// use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Select;

class Meta_Box_Test extends Test_Case {
	public function test_register_meta_box() {
		global $wp_meta_boxes;

		$metabox = Meta_Box::get_instance( 'Metabox 1' )
			->append_to( Post_Type::get_instance( 'Post' ) );
		$metabox->register_meta_box();

		$this->assertTrue( array_key_exists( $metabox->get_id(), $wp_meta_boxes['post']['advanced']['default'] ) );
	}

	public function test_set_post_type() {
		$metabox   = Meta_Box::get_instance( 'Metabox 2' );
		$post_type = Post_Type::get_instance( 'Post type 1' );
		$metabox->append_to( $post_type );

		$post_types = $this->get_private_property( $metabox, 'post_types' );

		$this->assertEquals( $post_type->get_id(), $post_types[0]->get_id() );
	}

	public function test_add_and_show_field() {
		ob_start();
		$metabox = Meta_Box::get_instance( 'Metabox 3' )
			->append( Input::get_instance( 'Text Input' ) )
			->show_meta_box();
		$html    = ob_get_clean();

		$expected1 = '_nonce';
		$expected2 = '_wp_http_referer';
		$expected3 = 'name="text-input[0]"';

		$this->assertContains( $expected1, $html );
		$this->assertContains( $expected2, $html );
		$this->assertContains( $expected3, $html );
	}

	// public function test_show_meta_box() {
	// 	$metabox = Meta_Box::get_instance( 'Metabox 1' )
	// 		->append( Input::get_instance( 'Input' ) )
	// 		->append( Textarea::get_instance( 'Textarea' ) )
	// 		->append( Attachment::get_instance( 'Attachment' ) )
	// 		->append( Checkbox::get_instance( 'Checkbox' ) )
	// 		->append( Radio::get_instance( 'Radio' )->options( array( 'Radio 1', 'Radio 2' ) ) )
	// 		->append( Select::get_instance( 'Select' )->options( array( 'Select 1', 'Select 2' ) ) );

	// 	ob_start();
	// 	$metabox->show_meta_box();
	// 	$actual = ob_get_clean();

	// 	$this->assertContains( '<section class="wp-express metabox">', $actual );
	// 	$this->assertContains( '<input type="hidden" id="metabox-1_nonce" name="metabox-1_nonce"', $actual );
	// 	$this->assertContains( 'name="input[0]', $actual );
	// 	$this->assertContains( 'id="wp-express__field__textarea__textarea"', $actual );
	// 	$this->assertContains( 'name="checkbox[0]"', $actual );
	// 	$this->assertContains( 'for="wp-express__field__radio__radio__radio-1"', $actual );
	// 	$this->assertContains( 'for="wp-express__field__radio__radio__radio-2"', $actual );
	// 	$this->assertContains( '<option value="Select 1">Select 1</option>', $actual );
	// 	$this->assertContains( '<option value="Select 2">Select 2</option>', $actual );
	// }
}
