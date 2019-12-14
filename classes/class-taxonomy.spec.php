<?php
/**
 * Taxonomy Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   3.0.0
 */

use Sujin\Wordpress\WP_Express\Taxonomy;
use Sujin\Wordpress\WP_Express\Post_Type;

class Taxonomy_Test extends Test_Case {
	public function test_construct_and_register() {
		global $wp_taxonomies;

		// Create
		$post_type = Post_Type::get_instance( 'Post' );
		$taxonomy  = Taxonomy::get_instance( 'Taxonomy Test' );
		$taxonomy->append_to( $post_type );
		$taxonomy->register_taxonomy();

		$this->assertTrue(
			array_key_exists( $taxonomy->get_id(), $wp_taxonomies ),
			'😡 Taxonomy registration failed.',
		);

		$actual   = $wp_taxonomies[ $taxonomy->get_id() ]->object_type;
		$expected = array( 'post' );
		$this->assertEquals(
			$expected,
			$actual,
			'😡 Taxonomy registration failed.',
		);

		// Custom Post Type
		$post_type = Post_Type::get_instance( 'Post_Type Test' );
		$taxonomy->append_to( $post_type );
		$taxonomy->register_taxonomy();

		$actual = $wp_taxonomies[ $taxonomy->get_id() ]->object_type;
		$this->assertTrue(
			in_array( $post_type->get_id(), $actual ),
			'😡 Taxonomy registration failed.',
		);
	}

	public function test_get_post_types_strings() {
		$taxonomy  = Taxonomy::get_instance( 'Taxonomy Test Get Post Types' );
		$post_type = Post_Type::get_instance( 'Post_Type Test' );
		$taxonomy->append_to( $post_type );
		$post_types = $this->call_private_method( $taxonomy, 'get_post_types_strings' );

		$this->assertEquals( count( $post_types ), 1 );
		$this->assertEquals( $post_types[0], 'post_type-test' );

		$taxonomy->append_to( Post_Type::get_instance( 'Post' ) );
		$post_types = $this->call_private_method( $taxonomy, 'get_post_types_strings' );

		$this->assertEquals(
			2,
			count( $post_types ),
			'😡 get_post_types_strings() is not matched as expectation.',
		);
		$this->assertEquals(
			'post_type-test',
			$post_types[0],
			'😡 get_post_types_strings() is not matched as expectation.',
		);
		$this->assertEquals(
			'post',
			$post_types[1],
			'😡 get_post_types_strings() is not matched as expectation.',
		);
	}
}
