<?php
/**
 * Common class for post meta
 *
 * @package WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Meta_Box;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

abstract class Abs_Post_Meta_Element extends Abs_Base_Element {
	protected $metabox;

	protected function init(): void {
		add_action( 'init', array( $this, 'register_meta' ) );
	}

	protected function refresh_id( ?int $id = null ): void {
		if ( $this->object_id ) {
			return;
		}

		global $post;

		if ( empty( $post ) ) {
			return;
		}

		$this->object_id = $post->ID;
	}

	public function attach_to( Meta_Box $metabox ): Abs_Post_Meta_Element {
		$metabox_filter = self::PREFIX . '_meta_box_' . $metabox->get_id();

		add_filter( $metabox_filter, array( $this, 'get_form' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

		$this->metabox = $metabox;
		return $this;
	}

	private function update( int $post_id, $value ): void {
		delete_post_meta( $post_id, $this->get_id() );

		if ( $this->option->single ) {
			$value = is_array( $value ) ? $value[0] : $value;
			update_post_meta( $post_id, $this->get_id(), $value );
			return;
		}

		foreach ( $value as $single_value ) {
			add_post_meta( $post_id, $this->get_id(), $single_value );
		}
	}

	public function get_form( string $output, ?int $id = null ): string {
		ob_start();
		$this->render( $id );
		return $output . ob_get_clean();
	}

	public function save_post( int $post_id ) {
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}

		$nonce = $_POST[ $this->metabox->get_id() . '_nonce' ] ?? null;
		if ( ! wp_verify_nonce( $nonce, $this->metabox->get_id() ) ) {
			return;
		}

		$post = get_post( $post_id );

		foreach ( $this->metabox->get_parents() as $parent_post_type ) {
			if ( $post->post_type === $parent_post_type ) {
				$value = $_POST[ $this->get_id() ] ?? false;
				$this->update( $post_id, $value );
			}
		}
	}

	public function register_meta() {
		$args = array(
			'type'         => 'string',
			'single'       => $this->option->single,
			'show_in_rest' => $this->option->show_in_rest,
		);
		register_meta( 'post', $this->get_id(), $args );
	}

	protected function refresh_value(): void {
		$this->value = get_post_meta( $this->object_id, $this->get_id(), $this->option->single );
	}

	protected function render_wrapper_open(): void {
		$class = explode( '\\', get_called_class() );
		$class = strtolower( array_pop( $class ) );

		?>
		<section
			class="<?php echo esc_attr( self::PREFIX ); ?> post-meta-wrap <?php echo esc_attr( $class ); ?>"
		>
			<label for="<?php echo esc_attr( self::PREFIX ); ?>__field__<?php echo esc_attr( $class ); ?>__<?php echo esc_attr( $this->get_id() ); ?>">
				<?php echo esc_html( $this->get_name() ); ?>
			</label>
		<?php
	}

	protected function render_wrapper_close(): void {
		echo '</section>';
	}
}
