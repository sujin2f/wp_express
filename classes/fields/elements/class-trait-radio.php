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

trait Trait_Radio {
	protected $_defaults_attributes = array(
		'class' => 'regular-text',
	);

	protected function _is_available(): bool {
		return ! empty( $this->_options['options'] );
	}

	protected function _render_form() {
		foreach ( $this->_options['options'] as $name => $option ) {
			$name    = is_numeric( $name ) ? $option : $name;
			$key     = sanitize_title( $name );
			$checked = ( $option == $this->_attributes['value'] ) ? 'checked="checked"' : '';

			?>
			<label for="<?php echo esc_attr( $this->get_id() ); ?>[<?php echo esc_attr( $key ); ?>]">
				<input
					type="radio"
					id="<?php echo esc_attr( $this->get_id() ); ?>[<?php echo esc_attr( $key ); ?>]"
					name="<?php echo esc_attr( $this->get_id() ); ?>"
					value="<?php echo esc_attr( $name ); ?>"
					<?php echo $checked; ?>
					<?php $this->_render_attributes(); ?>
				/>
				<?php echo esc_html( $name ); ?>
			</label>
			<?php
		}
	}
}
