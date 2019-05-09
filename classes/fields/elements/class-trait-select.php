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

trait Trait_Select {
	protected $_defaults_attributes = array(
		'class' => 'regular-text',
	);

	protected function _is_available(): bool {
		return ! empty( $this->_options['options'] );
	}

	protected function _render_form() {
		?>
		<select
			id="input--<?php echo esc_attr( $this->get_id() ); ?>"
			name="<?php echo esc_attr( $this->get_id() ); ?>"
			<?php $this->_render_attributes(); ?>
		>
			<option>== Select Option ==</option>
		<?php
		foreach ( $this->_options['options'] as $name => $option ) {
			$name     = is_numeric( $name ) ? $option : $name;
			$key      = sanitize_title( $name );
			$selected = ( $option == $this->_attributes['value'] ) ? ' selected="selected"' : '';

			echo '<option value="' . esc_attr( $name ) . '"' . $selected . '>' . esc_attr( $name ) . '</option>';
		}
		echo '</select>';
	}
}
