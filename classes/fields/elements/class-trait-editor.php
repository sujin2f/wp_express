<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

trait Trait_Editor {
	protected $DATA_TYPE = 'string';

	protected function is_single(): bool {
		return true;
	}

	protected function render_form_field(): void {
		echo '<section class="' . esc_attr( self::PREFIX ) . ' field editor">';
		wp_editor( stripcslashes( $this->value ), $this->get_id() );
		echo '</section>';
	}
}
