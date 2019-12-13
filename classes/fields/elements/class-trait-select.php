<?php
/**
 * Interface for Fields
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

trait Trait_Select {
	protected $DATA_TYPE = 'string';

	protected function init(): void {
		$this->argument->set( 'class', 'postform' );
		parent::init();
	}

	protected function is_available(): bool {
		return ! empty( $this->argument->get( 'options' ) );
	}

	protected function render_form_field(): void {
		$is_single = $this->is_single();
		$value     = $is_single ? array( $this->value ) : $this->value;
		?>
		<section class="<?php echo esc_attr( self::PREFIX ); ?> field select">
			<select
				id="<?php echo esc_attr( self::PREFIX ); ?>__field__select__<?php echo esc_attr( $this->get_id() ); ?>"
				name="<?php echo esc_attr( $this->get_id() ); ?>[]"
				<?php echo $this->is_single() ? '' : 'multiple'; ?>
				<?php $this->argument->render_attributes(); ?>
			>
				<?php echo $this->is_single() ? '<option value="">== Select Option ==</option>' : ''; ?>
				<?php
				foreach ( $this->argument->get( 'options' ) as $name => $option ) {
					$name     = is_numeric( $name ) ? $option : $name;
					$key      = sanitize_title( $name );
					$selected = ( in_array( $option, $value, true ) ) ? ' selected="selected"' : '';

					echo '<option value="' . esc_attr( $name ) . '"' . $selected . '>' . esc_attr( $name ) . '</option>';
				}
				echo '</select>';
				echo '</section>';
	}
}
