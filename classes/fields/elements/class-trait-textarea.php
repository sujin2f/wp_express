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

trait Trait_Textarea {
	protected $DATA_TYPE = 'string';

	protected function init(): void {
		$this->option->class = 'large-text code';
		$this->option->rows  = 10;
		$this->option->cols  = 50;
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
				<?php $this->option->render_attributes(); ?>
			><?php echo $this->value; ?></textarea>
		</section>
		<?php
	}
}
