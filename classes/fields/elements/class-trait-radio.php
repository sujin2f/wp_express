<?php
/**
 * Interface for Fields
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

trait Trait_Radio {
	protected $DATA_TYPE = 'string';

	protected function init(): void {
		$this->argument->set( 'class', 'tog' );
		parent::init();
	}

	protected function is_available(): bool {
		
		return ! empty( $this->argument->get( 'options' ) );
	}

	protected function is_single(): bool {
		return true;
	}

	protected function render_form_field(): void {
		echo '<section class="' . esc_attr( self::PREFIX ) . ' field radio">';
		echo '<fieldset>';

		if ( ! empty( $this->argument->get( 'legend' ) ) ) {
			echo '<legend class="screen-reader-text"><span>' . esc_html( $this->argument->get( 'legend' ) ) . '</span></legend>';
		}

		foreach ( $this->argument->get( 'options' ) as $name => $option ) {
			$name    = is_numeric( $name ) ? $option : $name;
			$key     = sanitize_title( $name );
			$checked = ( $option == $this->value ) ? 'checked="checked"' : '';

			?>
			<p>
				<label for="<?php echo esc_attr( self::PREFIX ); ?>__field__radio__<?php echo esc_attr( $this->get_id() ); ?>__<?php echo esc_attr( $key ); ?>">
					<input
						type="radio"
						id="<?php echo esc_attr( self::PREFIX ); ?>__field__radio__<?php echo esc_attr( $this->get_id() ); ?>__<?php echo esc_attr( $key ); ?>"
						name="<?php echo esc_attr( $this->get_id() ); ?>"
						value="<?php echo esc_attr( $name ); ?>"
						<?php echo $checked; ?>
						<?php $this->argument->render_attributes(); ?>
					/>
					<?php echo esc_html( $name ); ?>
				</label>
			</p>
			<?php
		}
		echo '</fieldset>';
		echo '</section>';
	}
}
