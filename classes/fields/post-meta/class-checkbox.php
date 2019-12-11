<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields\Post_Meta;

use Sujin\Wordpress\WP_Express\Fields\Post_Meta_Component;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Checkbox;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Checkbox extends Post_Meta_Component {
	use Trait_Checkbox;

	protected function render_wrapper_open(): void {
		?>
		<section
			id="<?php echo esc_attr( self::PREFIX ); ?>--post-meta-wrap--checkbox--<?php echo esc_attr( $this->get_id() ); ?>"
			class="<?php echo esc_attr( self::PREFIX ); ?> post-meta-wrap checkbox"
		>
			<label for="<?php echo esc_attr( self::PREFIX ); ?>__field__checkbox__<?php echo esc_attr( $this->get_id() ); ?>">
		<?php
	}

	protected function render_wrapper_close(): void {
		echo esc_html( $this->get_name() ) . '</label></section>';
	}
}
