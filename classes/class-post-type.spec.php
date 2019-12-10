<?php
use Sujin\Wordpress\WP_Express\Post_Type;

class Post_Type_Test extends Test_Case {
	public function test_show_in_rest() {
		$post_type = Post_Type::get_instance( 'Test Type' );
		$post_type = $post_type->show_in_rest( true );
		$arguments = $this->get_private_property( $post_type, 'arguments' );
		$this->assertTrue( $arguments->show_in_rest );
	}

	public function test_register_post_type() {
		// Custom Post Type
		Post_Type::get_instance( 'Test Type' )
			->register_post_type();

		$this->assertNotNull( get_post_type_object( 'test-type' ) );

		// Custom RESTful Post Type
		Post_Type::get_instance( 'Rest Test' )
			->show_in_rest( true )
			->register_post_type();

		$post_type = get_post_type_object( 'rest-test' );
		$this->assertNotNull( $post_type );
		$this->assertTrue( $post_type->show_in_rest );

		// Default Post Type
		Post_Type::get_instance( 'Post' )
			->show_in_rest( false )
			->register_post_type();
		$post_type = get_post_type_object( 'post' );
		$this->assertFalse( $post_type->show_in_rest );
	}
}
