<?php
/**
 * Interface for Fields
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

trait Trait_Checkbox {
	protected $data_type = 'string';

	protected function is_single(): bool {
		return $this->argument->get( 'options' ) ? false : true;
	}

	protected function render_form_field(): void {
		$is_single = $this->is_single();
		$value     = $is_single ? array( $this->value ) : $this->value;
		$options   = $is_single ? array( $this->get_name() ) : $this->argument->get( 'options' );

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
						<?php $this->argument->render_attributes(); ?>
					/>
					<?php echo esc_html( $option ); ?>
				</label>
			</p>
			<?php
		}
	}
}
