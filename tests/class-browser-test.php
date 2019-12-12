<?php
/**
 * Admin Class
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
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

final class BrowserTest {
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
		Setting::get_instance( 'Test Setting Block' )
			// Single Attachment
			->append(
				Option_Attachment::get_instance( 'Attachment Test' )
					->show_in_rest( true )
			)
			// Multi Attachment
			->append(
				Option_Attachment::get_instance( 'Multi Attachment Test' )
					->show_in_rest( true )
					->single( false )
			)
			// Single Input
			->append(
				Option_Input::get_instance( 'Input Test' )
					->show_in_rest( true )
			)
			// Single Input Color
			->append(
				Option_Input::get_instance( 'Input Test Color' )
					->show_in_rest( true )
					->type( 'color' )
			)
			// Single Input Date
			->append(
				Option_Input::get_instance( 'Input Test Date' )
					->show_in_rest( true )
					->type( 'date' )
			)
			// Single Input Email
			->append(
				Option_Input::get_instance( 'Input Test Email' )
					->show_in_rest( true )
					->type( 'email' )
			)
			// Single Input Number
			->append(
				Option_Input::get_instance( 'Input Test Number' )
					->show_in_rest( true )
					->type( 'number' )
			)
			// Single Input Range
			->append(
				Option_Input::get_instance( 'Input Test Range' )
					->show_in_rest( true )
					->type( 'range' )
			)
			// Single Input Tel
			->append(
				Option_Input::get_instance( 'Input Test Tel' )
					->show_in_rest( true )
					->type( 'tel' )
			)
			// Single Input Week
			->append(
				Option_Input::get_instance( 'Input Test Week' )
					->show_in_rest( true )
					->type( 'week' )
			)
			// Multi Input
			->append(
				Option_Input::get_instance( 'Multi Input Test' )
					->show_in_rest( true )
					->single( false )
			)
			// Textarea
			->append( Option_Textarea::get_instance( 'Textarea Test' )->show_in_rest( true ) )
			// Editor
			->append( Option_Textarea::get_instance( 'Editor Test' )->show_in_rest( true ) )
			// Radio
			->append(
				Option_Radio::get_instance( 'Radio Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
			)
			// Select
			->append(
				Option_Select::get_instance( 'Select Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
			)
			// Multi Select
			->append(
				Option_Select::get_instance( 'Multi Select Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
					->single( false )
			)
			// Select
			->append(
				Option_Checkbox::get_instance( 'Checkbox Test' )
					->show_in_rest( true )
			)
			// Multi Select
			->append(
				Option_Checkbox::get_instance( 'Multi Checkbox Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
			)
			->append_to( Admin::get_instance( '1st Depth' ) )
		;
	}

	private function test_post_type() {
		$post = Post_Type::get_instance( 'post' )
			->menu_position( 30 );

		$test = Post_Type::get_instance( 'Test' );
	}

	private function test_post_meta(): void {
		// Post Meta
		Meta_Box::get_instance( 'Test Metabox' )
			->append_to( Post_Type::get_instance( 'Post' ) )
			// Single Attachment
			->append(
				Meta_Attachment::get_instance( 'Attachment Test' )
					->show_in_rest( true )
			)
			// Multi Attachment
			->append(
				Meta_Attachment::get_instance( 'Multi Attachment Test' )
					->show_in_rest( true )
					->single( false )
			)
			// Single Input
			->append(
				Meta_Input::get_instance( 'Input Test' )
					->show_in_rest( true )
			)
			// Single Input Color
			->append(
				Meta_Input::get_instance( 'Input Test Color' )
					->show_in_rest( true )
					->type( 'color' )
			)
			// Single Input Date
			->append(
				Meta_Input::get_instance( 'Input Test Date' )
					->show_in_rest( true )
					->type( 'date' )
			)
			// Single Input Email
			->append(
				Meta_Input::get_instance( 'Input Test Email' )
					->show_in_rest( true )
					->type( 'email' )
			)
			// Single Input Number
			->append(
				Meta_Input::get_instance( 'Input Test Number' )
					->show_in_rest( true )
					->type( 'number' )
			)
			// Single Input Range
			->append(
				Meta_Input::get_instance( 'Input Test Range' )
					->show_in_rest( true )
					->type( 'range' )
			)
			// Single Input Tel
			->append(
				Meta_Input::get_instance( 'Input Test Tel' )
					->show_in_rest( true )
					->type( 'tel' )
			)
			// Single Input Week
			->append(
				Meta_Input::get_instance( 'Input Test Week' )
					->show_in_rest( true )
					->type( 'week' )
			)
			// Multi Input
			->append(
				Meta_Input::get_instance( 'Multi Input Test' )
					->show_in_rest( true )
					->single( false )
			)
			// Textarea
			->append( Meta_Textarea::get_instance( 'Textarea Test' )->show_in_rest( true ) )
			// Editor
			->append( Meta_Editor::get_instance( 'Editor Test' )->show_in_rest( true ) )
			// Radio
			->append(
				Meta_Radio::get_instance( 'Radio Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
			)
			// Select
			->append(
				Meta_Select::get_instance( 'Select Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
			)
			// Multi Select
			->append(
				Meta_Select::get_instance( 'Multi Select Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
					->single( false )
			)
			// Select
			->append(
				Meta_Checkbox::get_instance( 'Checkbox Test' )
					->show_in_rest( true )
			)
			// Multi Select
			->append(
				Meta_Checkbox::get_instance( 'Multi Checkbox Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
			)
		;
	}

	private function test_taxonomy() {
		// Taxonomy
		$taxonomy = Taxonomy::get_instance( 'New Taxonomy' );
		$tag      = Taxonomy::get_instance( 'Tag' );

		Taxonomy::get_instance( 'Category' )
			->append(
				Term_Meta_Attachment::get_instance( 'Attachment Test' )
					->show_in_rest( true )
			)
			// Multi Attachment
			->append(
				Term_Meta_Attachment::get_instance( 'Multi Attachment Test' )
					->show_in_rest( true )
					->single( false )
			)
			// Single Input
			->append(
				Term_Meta_Input::get_instance( 'Input Test' )
					->show_in_rest( true )
			)
			// Single Input Color
			->append(
				Term_Meta_Input::get_instance( 'Input Test Color' )
					->show_in_rest( true )
					->type( 'color' )
			)
			// Single Input Date
			->append(
				Term_Meta_Input::get_instance( 'Input Test Date' )
					->show_in_rest( true )
					->type( 'date' )
			)
			// Single Input Email
			->append(
				Term_Meta_Input::get_instance( 'Input Test Email' )
					->show_in_rest( true )
					->type( 'email' )
			)
			// Single Input Number
			->append(
				Term_Meta_Input::get_instance( 'Input Test Number' )
					->show_in_rest( true )
					->type( 'number' )
			)
			// Single Input Range
			->append(
				Term_Meta_Input::get_instance( 'Input Test Range' )
					->show_in_rest( true )
					->type( 'range' )
			)
			// Single Input Tel
			->append(
				Term_Meta_Input::get_instance( 'Input Test Tel' )
					->show_in_rest( true )
					->type( 'tel' )
			)
			// Single Input Week
			->append(
				Term_Meta_Input::get_instance( 'Input Test Week' )
					->show_in_rest( true )
					->type( 'week' )
			)
			// Multi Input
			->append(
				Term_Meta_Input::get_instance( 'Multi Input Test' )
					->show_in_rest( true )
					->single( false )
			)
			// Textarea
			->append( Term_Meta_Textarea::get_instance( 'Textarea Test' )->show_in_rest( true ) )
			// Editor
			->append( Term_Meta_Editor::get_instance( 'Editor Test' )->show_in_rest( true ) )
			// Radio
			->append(
				Term_Meta_Radio::get_instance( 'Radio Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
			)
			// Select
			->append(
				Term_Meta_Select::get_instance( 'Select Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
			)
			// Multi Select
			->append(
				Term_Meta_Select::get_instance( 'Multi Select Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
					->single( false )
			)
			// Select
			->append(
				Term_Meta_Checkbox::get_instance( 'Checkbox Test' )
					->show_in_rest( true )
			)
			// Multi Select
			->append(
				Term_Meta_Checkbox::get_instance( 'Multi Checkbox Test' )
					->options( array( 'Selection 1', 'Selection 2' ) )
					->show_in_rest( true )
			)
		;

		// Term Meta to Existing Taxonomy
		Term_Meta_Input::get_instance( 'Input Test' )
			->append_to( Taxonomy::get_instance( 'Category' ) )
			->append_to( Taxonomy::get_instance( 'Tag' ) );
	}
}
