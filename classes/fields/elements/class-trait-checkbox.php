<?php
/**
 * Interface for Fields
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

trait Trait_Checkbox {
	protected $DATA_TYPE = 'string';

	protected function is_single(): bool {
		return $this->option->options ? false : true;
	}

	protected function render_form_field(): void {
		$is_single = $this->is_single();
		$value     = $is_single ? array( $this->value ) : $this->value;
		$options   = $is_single ? array( $this->get_name() ) : $this->option->options;

		foreach ( $options as $key => $option ) {
			?>
			<section class="<?php echo esc_attr( self::PREFIX ); ?> field checkbox">
				<label for="<?php echo esc_attr( self::PREFIX ); ?>__field__checkbox__<?php echo esc_attr( $this->get_id() ); ?>__<?php echo esc_attr( $key ); ?>">
					<input
						id="<?php echo esc_attr( self::PREFIX ); ?>__field__checkbox__<?php echo esc_attr( $this->get_id() ); ?>__<?php echo esc_attr( $key ); ?>"
						name="<?php echo esc_attr( $this->get_id() ); ?>[<?php echo esc_attr( $key ); ?>]"
						type="checkbox"
						value="<?php echo esc_attr( $option ); ?>"
						<?php echo in_array( $option, $value, true ) ? 'checked="checked"' : ''; ?>
						<?php $this->option->render_attributes(); ?>
					/>
					<?php echo esc_html( $option ); ?>
				</label>
			</p>
			<?php
		}
	}
}
