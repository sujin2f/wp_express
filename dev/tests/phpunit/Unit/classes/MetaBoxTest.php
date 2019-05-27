<?php
namespace Sujin\Wordpress\WP_Express\Tests\Unit;

use Sujin\Wordpress\WP_Express\Tests\Unit\TestCase;

use Sujin\Wordpress\WP_Express\Meta_Box;
use Sujin\Wordpress\WP_Express\Post_Type;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Input;

class MetaBoxTest extends TestCase {
	public function test_register_meta_box() {
		$metabox = Meta_Box::get_instance( 'Metabox 1' );
		$metabox->_register_meta_box();

		global $wp_meta_boxes;

		$this->assertTrue( array_key_exists( $metabox->get_id(), $wp_meta_boxes['post']['advanced']['default'] ) );
	}

	public function test_set_post_type() {
		$metabox   = Meta_Box::get_instance( 'Metabox 2' );
		$post_type = Post_Type::get_instance( 'Post type 1' );
		$metabox->attach_to( $post_type );

		$post_types = $this->get_private_property( $metabox, '_post_types' );

		$this->assertEquals( $post_type->get_id(), $post_types[0]->get_id() );
	}

	public function test_add_and_show_field() {
		ob_start();
		$metabox = Meta_Box::get_instance( 'Metabox 3' )
			->add( Input::get_instance( 'Text Input' ) )
			->_show_meta_box();
		$html    = ob_get_clean();

		$expected1 = '_nonce';
		$expected2 = '_wp_http_referer';
		$expected3 = '<label for="wp-express__field__input__text-input">';

		$this->assertContains( $expected1, $html );
		$this->assertContains( $expected2, $html );
		$this->assertContains( $expected3, $html );
	}

	public function test_get_parents() {
		$metabox  = Meta_Box::get_instance( 'Metabox 1' );
		$actual   = $metabox->_get_parents();
		$expected = array( 'post' );

		$this->assertEquals( $actual, $expected );

		$post_type = Post_Type::get_instance( 'Post type 1' );
		$metabox->attach_to( $post_type );
		$actual   = $metabox->_get_parents();
		$expected = array( $post_type->get_id() );
		$this->assertEquals( $actual, $expected );
	}

}
