<?php
/**
 * Post_Type Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   3.0.0
 */

use Sujin\Wordpress\WP_Express\Post_Type;

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
}
