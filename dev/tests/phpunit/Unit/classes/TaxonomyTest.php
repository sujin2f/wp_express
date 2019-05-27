<?php
namespace Sujin\Wordpress\WP_Express\Tests\Unit;

use Sujin\Wordpress\WP_Express\Tests\Unit\TestCase;

use Sujin\Wordpress\WP_Express\Taxonomy;
use Sujin\Wordpress\WP_Express\Post_Type;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Input;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Textarea;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Editor;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Attachment;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Checkbox;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Radio;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Select;

class TaxonomyTest extends TestCase {
	public function test_construct_and_register() {
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

	public function test_get_post_types_strings() {
		$taxonomy = Taxonomy::get_instance( 'Taxonomy Test Get Post Types' );
		$post_types = $this->call_private_method( $taxonomy, '_get_post_types_strings' );

		$this->assertEquals( count( $post_types ), 1 );
		$this->assertEquals( $post_types[0], 'post' );

		$post_type = Post_Type::get_instance( 'Post_Type Test' );
		$taxonomy->attach_to( $post_type );
		$post_types = $this->call_private_method( $taxonomy, '_get_post_types_strings' );

		$this->assertEquals( count( $post_types ), 1 );
		$this->assertEquals( $post_types[0], 'post_type-test' );

		$taxonomy->attach_to( 'post' );
		$post_types = $this->call_private_method( $taxonomy, '_get_post_types_strings' );

		$this->assertEquals( count( $post_types ), 2 );
		$this->assertEquals( $post_types[0], 'post_type-test' );
		$this->assertEquals( $post_types[1], 'post' );
	}

	public function test_term_meta() {
		$taxonomy = Taxonomy::get_instance( 'Term Meta Test' )
			->add( Input::get_instance( 'Input' ) )
			->add( Textarea::get_instance( 'Textarea' ) )
			->add( Attachment::get_instance( 'Attachment' ) )
			->add( Checkbox::get_instance( 'Checkbox' ) )
			->add( Radio::get_instance( 'Radio' )->options( array( 'Radio 1', 'Radio 2' ) ) )
			->add( Select::get_instance( 'Select' )->options( array( 'Select 1', 'Select 2' ) ) );

		$_GET['tag_ID'] = 1;
		ob_start();
		do_action( $taxonomy->get_id() . '_edit_form_fields' );
		$actual = ob_get_clean();

		$this->assertContains( '<label for="wp-express__field__input__input">', $actual );
		$this->assertContains( '<label for="wp-express__field__textarea__textarea">', $actual );
		$this->assertContains( '<label for="wp-express__field__attachment__attachment">', $actual );
		$this->assertContains( '<label for="wp-express__field__checkbox__checkbox">', $actual );
		$this->assertContains( '<label for="wp-express__field__radio__radio">', $actual );
		$this->assertContains( '<label for="wp-express__field__radio__radio__radio-1">', $actual );
		$this->assertContains( '<label for="wp-express__field__radio__radio__radio-2">', $actual );
		$this->assertContains( '<label for="wp-express__field__select__select">', $actual );
		$this->assertContains( '<option value="Select 1">Select 1</option>', $actual );
		$this->assertContains( '<option value="Select 2">Select 2</option>', $actual );
	}
}
