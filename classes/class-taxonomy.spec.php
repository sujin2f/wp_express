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
// use Sujin\Wordpress\WP_Express\Fields\Term_Meta\{
// 	Input,
// 	Textarea,
// 	Editor,
// 	Attachment,
// 	Checkbox,
// 	Radio,
// 	Select,
// };

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

	// public function test_term_meta() {
	// 	$taxonomy = Taxonomy::get_instance( 'Term Meta Test' )
	// 		->append( Input::get_instance( 'Input' ) )
	// 		->append( Textarea::get_instance( 'Textarea' ) )
	// 		->append( Attachment::get_instance( 'Attachment' ) )
	// 		->append( Checkbox::get_instance( 'Checkbox' ) )
	// 		->append( Radio::get_instance( 'Radio' )->options( array( 'Radio 1', 'Radio 2' ) ) )
	// 		->append( Select::get_instance( 'Select' )->options( array( 'Select 1', 'Select 2' ) ) );

	// 	$taxonomy->register_taxonomy();

	// 	$post_type = Post_Type::get_instance( 'Post_Type Test' );
	// 	$taxonomy->append_to( $post_type );

	// 	$term = wp_insert_term( 'Term', $taxonomy->get_id() );
	// 	$term = get_term( $term['term_id'], $taxonomy->get_id() );
	
	// 	ob_start();
	// 	do_action( $taxonomy->get_id() . '_edit_form_fields', $term );
	// 	$actual = ob_get_clean();

	// 	$this->assertContains( '<label for="wp-express__field__input__input">', $actual );
	// 	$this->assertContains( '<label for="wp-express__field__textarea__textarea">', $actual );
	// 	$this->assertContains( '<label for="wp-express__field__attachment__attachment">', $actual );
	// 	$this->assertContains( '<label for="wp-express__field__checkbox__checkbox">', $actual );
	// 	$this->assertContains( '<label for="wp-express__field__radio__radio">', $actual );
	// 	$this->assertContains( '<label for="wp-express__field__radio__radio__radio-1">', $actual );
	// 	$this->assertContains( '<label for="wp-express__field__radio__radio__radio-2">', $actual );
	// 	$this->assertContains( '<label for="wp-express__field__select__select">', $actual );
	// 	$this->assertContains( '<option value="Select 1">Select 1</option>', $actual );
	// 	$this->assertContains( '<option value="Select 2">Select 2</option>', $actual );
	// }
}
