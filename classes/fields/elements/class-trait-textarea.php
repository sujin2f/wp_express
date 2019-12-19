<?php
/**
 * Interface for Fields
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

trait Trait_Textarea {
	protected $data_type = 'string';

	protected function init(): void {
		$this->argument->set( 'class', 'large-text code' );
		$this->argument->set( 'rows', 10 );
		$this->argument->set( 'cols', 50 );
		parent::init();
	}

	protected function is_single(): bool {
		return true;
	}

	protected function render_form_field(): void {
		?>
		<section class="<?php echo esc_attr( self::PREFIX ); ?> field textarea">
			<textarea
				id="<?php echo esc_attr( self::PREFIX ); ?>__field__textarea__<?php echo esc_attr( $this->get_id() ); ?>"
				name="<?php echo esc_attr( $this->get_id() ); ?>"
				<?php $this->argument->render_attributes(); ?>
			><?php echo $this->value; ?></textarea>
		</section>
		<?php
	}
}
