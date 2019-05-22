<?php
namespace Sujin\Wordpress\WP_Express\Tests\Unit;

use Sujin\Wordpress\WP_Express\Tests\Unit\TestCase;

use Sujin\Wordpress\WP_Express\Taxonomy;
use Sujin\Wordpress\WP_Express\Post_Type;
/*
use Sujin\Wordpress\WP_Express\Fields\Settings\Input;
use Sujin\Wordpress\WP_Express\Admin;
*/

class TaxonomyTest extends TestCase {
	public function test_construct() {
		global $wp_taxonomies;

		// Create
		$taxonomy = Taxonomy::get_instance( 'Taxonomy Test' );
		$taxonomy->_register_taxonomy();

		$this->assertTrue( array_key_exists( $taxonomy->get_id(), $wp_taxonomies ) );

		$actual   = $wp_taxonomies[ $taxonomy->get_id() ]->object_type;
		$expected = array( 'post' );
		$this->assertEquals( $actual, $expected );

		// Custom Post Type
		$post_type = Post_Type::get_instance( 'Post_Type Test' );
		$taxonomy->attach_to( $post_type );
		$taxonomy->_register_taxonomy();

		$actual   = $wp_taxonomies[ $taxonomy->get_id() ]->object_type;
		$expected = array( 'post', $post_type->get_id() );
		$this->assertEquals( $actual, $expected );
	}
}
