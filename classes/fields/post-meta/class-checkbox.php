<?php
/**
 * Interface for Fields
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express\Fields\Post_Meta;

use Sujin\Wordpress\WP_Express\Fields\Abstract_Filed_Post_Meta;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Checkbox;

class Checkbox extends Abstract_Filed_Post_Meta {
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
