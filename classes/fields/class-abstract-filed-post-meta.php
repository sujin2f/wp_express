<?php
/**
 * Common class for post meta
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Meta_Box;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;

abstract class Abstract_Filed_Post_Meta extends Abstract_Filed {
	use Trait_Multiton;

	/**
	 * @var Meta_Box
	 */
	public $metabox;

	public function append_to( Meta_Box $metabox ): self {
		$this->metabox = $metabox;
		return $this;
	}

	public function update( ?int $post_id = null, $value = null ): void {
		if ( is_null( $value ) ) {
			$value = $_POST[ $this->get_id() ] ?? null;
		}

		delete_post_meta( $post_id, $this->get_id() );

		if ( $this->is_single() ) {
			$value = is_array( $value ) ? $value[0] : $value;
			update_post_meta( $post_id, $this->get_id(), $value );
			return;
		}

		foreach ( $value ?? array() as $single_value ) {
			if ( ! $single_value ) {
				continue;
			}

			add_post_meta( $post_id, $this->get_id(), $single_value );
		}
	}

	/**
	 * Register post meta
	 * https://developer.wordpress.org/reference/functions/register_meta/
	 */
	public function register_meta() {
		$args = array(
			'type'         => $this->get_data_type(),
			'single'       => $this->is_single(),
			'show_in_rest' => $this->argument->get( 'show_in_rest' ),
		);
		register_meta( 'post', $this->get_id(), $args );
	}

	protected function init(): void {
		add_action( 'init', array( $this, 'register_meta' ) );
	}

	protected function render_form_wrapper_open(): void {
		$class         = $this->get_called_class();
		$section_class = self::PREFIX . ' post-meta-wrap ' . $class;

		?>
		<section class="<?php echo esc_attr( $section_class ); ?>">
			<label>
				<?php echo esc_html( $this->get_name() ); ?>
			</label>
		<?php
	}

	protected function render_form_wrapper_close(): void {
		?>
		</section>
		<?php
	}

	protected function refresh_id( ?int $id = null ): void {
		if ( $this->wp_object_id ) {
			return;
		}

		if ( ! is_null( $id ) ) {
			$this->wp_object_id = $id;
			return;
		}

		global $post;

		if ( empty( $post ) ) {
			return;
		}

		$this->wp_object_id = $post->ID;
	}

	protected function refresh_value(): void {
		$this->value = get_post_meta( $this->wp_object_id, $this->get_id(), $this->is_single() );
	}

	protected function get_data_type(): string {
		return $this->DATA_TYPE;
	}
}
