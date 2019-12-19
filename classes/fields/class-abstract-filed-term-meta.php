<?php
/**
 * Abstract class for term meta fields
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Taxonomy;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;
use WP_Term;

abstract class Abstract_Filed_Term_Meta extends Abstract_Filed {
	use Trait_Multiton;

	/**
	 * @var Taxonomy[]
	 */
	private $taxonomies = array();

	public function append_to( Taxonomy $taxonomy ): self {
		$this->taxonomies[] = $taxonomy;

		add_action( $taxonomy->get_id() . '_edit_form_fields', array( $this, 'render_setting_form' ), 25 );
		add_action( 'edited_' . $taxonomy->get_id(), array( $this, 'update' ), 25 );

		return $this;
	}

	public function render_setting_form( WP_Term $term ) {
		$this->render_form( $term->term_id );
	}

	public function update( ?int $term_id = null, $value = null ): void {
		if ( is_null( $value ) ) {
			$value = $_POST[ $this->get_id() ] ?? null;
		}

		delete_term_meta( $term_id, $this->get_id() );

		if ( $this->is_single() ) {
			$value = is_array( $value ) ? $value[0] : $value;
			update_term_meta( $term_id, $this->get_id(), $value );
			return;
		}

		foreach ( $value ?? array() as $single_value ) {
			if ( ! $single_value ) {
				continue;
			}

			add_term_meta( $term_id, $this->get_id(), $single_value );
		}
	}

	public function register_meta() {
		$args = array(
			'type'         => $this->get_data_type(),
			'single'       => $this->is_single(),
			'show_in_rest' => $this->argument->get( 'show_in_rest' ),
		);
		register_meta( 'term', $this->get_id(), $args );
	}

	protected function init(): void {
		add_action( 'init', array( $this, 'register_meta' ) );
	}

	protected function render_form_wrapper_open(): void {
		$class = explode( '\\', get_called_class() );
		$class = strtolower( array_pop( $class ) );

		?>
		<tr class="form-field term-<?php echo esc_attr( $this->get_id() ); ?>-wrap">
			<th scope="row">
				<label for="<?php echo esc_attr( self::PREFIX ); ?>__field__<?php echo esc_attr( $class ); ?>__<?php echo esc_attr( $this->get_id() ); ?>">
					<?php echo esc_html( $this->get_name() ); ?>
				</label>
			</th>
			<td>
		<?php
	}

	protected function render_form_wrapper_close(): void {
		echo '</td></tr>';
	}

	protected function refresh_id( ?int $id = null ): void {
		if ( $this->wp_object_id ) {
			return;
		}

		if ( ! is_null( $id ) ) {
			$this->wp_object_id = $id;
			return;
		}

		if ( empty( $_GET['tag_ID'] ?? null ) ) {
			return;
		}
		$this->wp_object_id = $_GET['tag_ID'];
	}

	protected function refresh_value(): void {
		$this->value = get_term_meta( $this->wp_object_id, $this->get_id(), $this->is_single() );
	}

	protected function get_data_type(): string {
		return $this->data_type;
	}

	public function get_parents(): array {
		$taxonomies = array();

		foreach ( $this->taxonomies as $taxonomy ) {
			$taxonomies[] = $taxonomy->get_id();
		}

		return $taxonomies;
	}
}
