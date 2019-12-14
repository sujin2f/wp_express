<?php
/**
 * Interface for Fields
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

trait Trait_Input {
	protected $data_type  = 'string';
	private $number_types = array( 'range', 'number' );

	protected function get_data_type(): string {
		return in_array( $this->argument->get( 'type' ), $this->number_types, true ) ? 'number' : $this->data_type;
	}

	protected function init(): void {
		$this->argument->set( 'class', 'regular-text code input__items__item' );
		$this->argument->set( 'type', 'text' );
		parent::init();
	}

	protected function render_form_field(): void {
		$is_single = $this->is_single();
		$value     = $is_single
			? array( $this->value )
			: array_merge(
				$this->value,
				array( null ),
			);
		?>
		<section
			class="<?php echo esc_attr( self::PREFIX ); ?> field input input__items"
			data-id="<?php echo esc_attr( $this->get_id() ); ?>"
			data-next-index="<?php echo esc_attr( count( $value ) ); ?>"
			<?php echo $is_single ? '' : esc_attr( 'data-multiple' ); ?>
		>
			<?php foreach ( $value as $index => $item_value ) : ?>
				<input
					name="<?php echo esc_attr( $this->get_id() ); ?>[<?php echo esc_attr( $index ); ?>]"
					value="<?php echo esc_attr( $item_value ); ?>"
					<?php $this->argument->render_attributes(); ?>
				/>
			<?php endforeach; ?>
		</section>
		<?php
	}
}
