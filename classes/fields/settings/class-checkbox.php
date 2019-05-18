<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields\Settings;

use Sujin\Wordpress\WP_Express\Fields\Abs_Setting_Element;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Checkbox;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Checkbox extends Abs_Setting_Element {
	use Trait_Checkbox;

	protected function _render_wrapper_open() {
		?>
		<section
			id="<?php echo esc_attr( self::PREFIX ); ?>--option-wrap--checkbox--<?php echo esc_attr( $this->get_id() ); ?>"
			class="<?php echo esc_attr( self::PREFIX ); ?> option-wrap checkbox"
		>
			<label for="<?php echo esc_attr( self::PREFIX ); ?>__field__checkbox__<?php echo esc_attr( $this->get_id() ); ?>">
		<?php
	}

	protected function _render_wrapper_close() {
		echo esc_html( $this->get_name() ) . '</label></section>';
	}
}
