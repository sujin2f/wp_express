<?php
/**
 * Post_Type Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   3.0.0
 */

use Sujin\Wordpress\WP_Express\Post_Type;
use Sujin\Wordpress\WP_Express\Meta_Box;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Textarea;

class Post_Type_Test extends Test_Case {
	public function test_show_in_rest() {
		$post_type = Post_Type::get_instance( 'Test Type' )->show_in_rest( true );
		$argument  = $this->get_private_property( $post_type, 'argument' );
		$this->assertTrue(
			$argument->get( 'show_in_rest' ),
			'😡 Argument assignment failed.',
		);
	}

	public function test_register_post_type() {
		// Custom Post Type
		Post_Type::get_instance( 'Test Type' )->register_post_type();

		$this->assertNotNull(
			get_post_type_object( 'test-type' ),
			'😡 Post type registration failed.',
		);

		// Custom RESTful Post Type
		Post_Type::get_instance( 'Rest Test' )
			->show_in_rest( true )
			->register_post_type();

		$post_type = get_post_type_object( 'rest-test' );
		$this->assertNotNull(
			$post_type,
			'😡 Post type registration failed.',
		);
		$this->assertTrue(
			$post_type->show_in_rest,
			'😡 Post type show_in_rest assignment failed.',
		);

		// Default Post Type
		Post_Type::get_instance( 'Post' )
			->show_in_rest( false )
			->register_post_type();
		$post_type = get_post_type_object( 'post' );
		$this->assertFalse(
			$post_type->show_in_rest,
			'😡 Default post type re-assignment failed.',
		);
	}

	public function test_append(): void {
		$post_type = Post_Type::get_instance( 'Test Post Type' );
		$meta_box  = Meta_Box::get_instance( 'Test Metabox' );

		$post_type->append( $meta_box );
		$actual = $meta_box->post_types;

		$this->assertEquals(
			$post_type,
			$actual[0],
			'😡 Post type - Meta box append not assigned.',
		);
	}

	public function test_save_post(): void {
		$post_type = Post_Type::get_instance( 'Test Post Type' );
		$meta_box  = Meta_Box::get_instance( 'Test Metabox' );
		$textarea  = Textarea::get_instance( 'Test Textarea' );
		$post      = $this->factory->post->create_and_get(
			array(
				'post_type' => $post_type->get_id(),
			)
		);

		$post_type->append( $meta_box );
		$meta_box->append( $textarea );

		$_POST = array(
			$meta_box->get_id() . '_nonce' => wp_create_nonce( $meta_box->get_id() ),
			$textarea->get_id()            => 'test',
		);
		$post_type->save_post( $post->ID, $post );

		$actual = get_post_meta( $post->ID, $textarea->get_id(), true );

		$this->assertEquals(
			'test',
			$actual,
			'😡 Saving post has failed.',
		);
	}
}
