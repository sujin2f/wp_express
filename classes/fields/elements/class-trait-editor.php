<?php
/**
 * Interface for Fields
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

trait Trait_Editor {
	protected $data_type = 'string';

	protected function is_single(): bool {
		return true;
	}

	protected function render_form_field(): void {
		echo '<section class="' . esc_attr( self::PREFIX ) . ' field editor">';
		wp_editor( stripcslashes( $this->value ), $this->get_id() );
		echo '</section>';
	}
}
