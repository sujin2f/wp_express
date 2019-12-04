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

trait Trait_Input {
	protected $defaults_attributes = array(
		'class' => 'regular-text code',
		'type'  => 'text',
	);

	protected function init(): void {
		$this->option->class = 'regular-text code';
		$this->option->type  = $this->option->type ?? 'text';
		parent::init();
	}

	protected function is_available(): bool {
		return true;
	}

	protected function render_form(): void {
		?>
		<section
			class="<?php echo esc_attr( self::PREFIX ); ?> field input"
			data-parent="<?php echo esc_attr( $this->get_id() ); ?>"
		>
			<input
				id="<?php echo esc_attr( self::PREFIX ); ?>__field__input__<?php echo esc_attr( $this->get_id() ); ?>"
				name="<?php echo esc_attr( $this->get_id() ); ?>"
				<?php $this->option->render_attributes(); ?>
			/>
		</section>
		<?php
	}
}
