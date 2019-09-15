<?php
/**
 * Admin Class
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Post_Type;

use Sujin\Wordpress\WP_Express\Setting;
use Sujin\Wordpress\WP_Express\Fields\Settings\Input as Option_Input;
use Sujin\Wordpress\WP_Express\Fields\Settings\Textarea as Option_Textarea;
use Sujin\Wordpress\WP_Express\Fields\Settings\Editor as Option_Editor;
use Sujin\Wordpress\WP_Express\Fields\Settings\Attachment as Option_Attachment;
use Sujin\Wordpress\WP_Express\Fields\Settings\Checkbox as Option_Checkbox;
use Sujin\Wordpress\WP_Express\Fields\Settings\Radio as Option_Radio;
use Sujin\Wordpress\WP_Express\Fields\Settings\Select as Option_Select;

use Sujin\Wordpress\WP_Express\Meta_Box;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Input as Meta_Input;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Textarea as Meta_Textarea;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Editor as Meta_Editor;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Attachment as Meta_Attachment;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Checkbox as Meta_Checkbox;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Radio as Meta_Radio;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Select as Meta_Select;

use Sujin\Wordpress\WP_Express\Taxonomy;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Input as Term_Meta_Input;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Textarea as Term_Meta_Textarea;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Editor as Term_Meta_Editor;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Attachment as Term_Meta_Attachment;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Checkbox as Term_Meta_Checkbox;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Radio as Term_Meta_Radio;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Select as Term_Meta_Select;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class BrowserTest {
	public function __construct() {
		$this->test_admin();
		$this->test_setting();
		$this->test_post_type();
		$this->test_post_meta();
		$this->test_taxonomy();
	}

	private function test_admin() {
		$first_depth = Admin::get_instance( '1st Depth' )
			->position( 100 )
			->icon( 'dashicons-awards' );
		Admin::get_instance( 'POSITION_OPTION' )
			->position( Admin::POSITION_OPTION );
		Admin::get_instance( 'POSITION_TOOLS' )
			->position( Admin::POSITION_TOOLS );
		Admin::get_instance( 'POSITION_USERS' )
			->position( Admin::POSITION_USERS );
		Admin::get_instance( 'POSITION_PLUGINS' )
			->position( Admin::POSITION_PLUGINS );
		Admin::get_instance( 'POSITION_COMMENTS' )
			->position( Admin::POSITION_COMMENTS );
		Admin::get_instance( 'POSITION_PAGES' )
			->position( Admin::POSITION_PAGES );
		Admin::get_instance( 'POSITION_POSTS' )
			->position( Admin::POSITION_POSTS );
		Admin::get_instance( 'POSITION_MEDIA' )
			->position( Admin::POSITION_MEDIA );
		Admin::get_instance( 'POSITION_DASHBOARD' )
			->position( Admin::POSITION_DASHBOARD );
		Admin::get_instance( 'POSITION_APPEARANCE' )
			->position( Admin::POSITION_APPEARANCE );
		Admin::get_instance( 'By Name' )
			->position( 'Test' )
			->plugin( 'Akismet Anti-Spam' );
		Admin::get_instance( 'By Admin' )
			->position( $first_depth );

		Admin::get_instance( 'Test depth' )
			->position( 20 );
	}

	private function test_setting() {
		// Settings
		$admin    = Admin::get_instance( 'Test Admin Page' );
		$settings = Setting::get_instance( 'Test Setting Block' );

		$option_input      = Option_Input::get_instance( 'Input Test' );
		$option_textarea   = Option_Textarea::get_instance( 'Textarea Test' );
		$option_editor     = Option_Editor::get_instance( 'Editor Test' );
		$option_attachment = Option_Attachment::get_instance( 'Attachment Test' );
		$option_checkbox   = Option_Checkbox::get_instance( 'Checkbox Test' );
		$option_radio      = Option_Radio::get_instance( 'Radio Test' )
			->options( array( 'Selection 1', 'Selection 2' ) );
		$option_select     = Option_Select::get_instance( 'Select Test' )
			->options( array( 'Selection 1', 'Selection 2' ) );

		$settings->add( $option_input )
			->add( $option_textarea )
			->add( $option_editor )
			->add( $option_attachment )
			->add( $option_checkbox )
			->add( $option_radio )
			->add( $option_select );

		$admin->add( $settings );

		$settings2     = Setting::get_instance( 'Test Setting Block 2' );
		$option_input2 = Option_Input::get_instance( 'Input Test' );
		$settings2->add( $option_input2 );
	}

	private function test_post_type() {
		$post = Post_Type::get_instance( 'post' )
			->menu_position( 30 );

		$test = Post_Type::get_instance( 'Test' );
	}

	private function test_post_meta() {
		$test = Post_Type::get_instance( 'Test' );
		// Custom Post Type's Meta
		$meta = Meta_Box::get_instance( 'Test Metabox for custom' )
			->attach_to( $test );

		$meta_input = Meta_Input::get_instance( 'Input Test 0' );
		$meta->add( $meta_input );

		// Post Meta
		$meta = Meta_Box::get_instance( 'Test Metabox' );

		$meta_input      = Meta_Input::get_instance( 'Input Test' );
		$meta_textarea   = Meta_Textarea::get_instance( 'Textarea Test' );
		$meta_editor     = Meta_Editor::get_instance( 'Editor Test' );
		$meta_attachment = Meta_Attachment::get_instance( 'Attachment Test' );
		$meta_checkbox   = Meta_Checkbox::get_instance( 'Checkbox Test' );
		$meta_radio      = Meta_Radio::get_instance( 'Radio Test' )
			->options( array( 'Selection 1', 'Selection 2' ) );
		$meta_select     = Meta_Select::get_instance( 'Select Test' )
			->options( array( 'Selection 1', 'Selection 2' ) );

		$meta->add( $meta_input )
			->add( $meta_textarea )
			->add( $meta_editor )
			->add( $meta_attachment )
			->add( $meta_checkbox )
			->add( $meta_radio )
			->add( $meta_select );
	}

	private function test_taxonomy() {
		// Taxonomy
		$taxonomy = Taxonomy::get_instance( 'New Taxonomy' );
		$tag      = Taxonomy::get_instance( 'Tag' );

		$term_meta_input      = Term_Meta_Input::get_instance( 'Input Test' );
		$term_meta_textarea   = Term_Meta_Textarea::get_instance( 'Textarea Test' );
		$term_meta_editor     = Term_Meta_Editor::get_instance( 'Editor Test' );
		$term_meta_attachment = Term_Meta_Attachment::get_instance( 'Attachment Test' );
		$term_meta_checkbox   = Term_Meta_Checkbox::get_instance( 'Checkbox Test' );
		$term_meta_radio      = Term_Meta_Radio::get_instance( 'Radio Test' )
			->options( array( 'Selection 1', 'Selection 2' ) );
		$term_meta_select     = Term_Meta_Select::get_instance( 'Select Test' )
			->options( array( 'Selection 1', 'Selection 2' ) );

		$tag->add( $term_meta_input )
			->add( $term_meta_textarea )
			->add( $term_meta_editor )
			->add( $term_meta_attachment )
			->add( $term_meta_checkbox )
			->add( $term_meta_radio )
			->add( $term_meta_select );

		// Term Meta to Existing Taxonomy
		$term_meta_input
			->attach_to( 'category' )
			->attach_to( 'post_tag' );
	}
}
