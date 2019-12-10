<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

abstract class Abs_Term_Meta_Element extends Abs_Base_Element {
	/**
	 * @var Abs_Post_Meta_Element[]
	 */
	protected static $multiton_container  = array();

	/**
	 * @var Taxonomy[]
	 */
	private $taxonomies = array();

	public function attach_to( Taxonomy $taxonomy ): Abs_Term_Meta_Element {
		$this->taxonomies[] = $taxonomy;

		add_action( $taxonomy->get_id() . '_edit_form_fields', array( $this, 'render' ), 25 );
		add_action( 'edited_' . $taxonomy->get_id(), array( $this, 'update' ), 25 );

		return $this;
	}

	public function update( ?int $term_id = null, $value = null ): void {
		if ( is_null( $value ) ) {
			$value = $_POST[ $this->get_id() ];
		}
		update_term_meta( $term_id, $this->get_id(), $value );
	}

	public function register_meta() {
		$args = array(
			'type'         => $this->get_data_type(),
			'single'       => $this->is_single(),
			'show_in_rest' => $this->option->show_in_rest,
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
		if ( $this->object_id ) {
			return;
		}

		if ( ! is_null( $id ) ) {
			$this->object_id = $id;
			return;
		}

		if ( empty( $_GET['tag_ID'] ?? null ) ) {
			return;
		}
		$this->object_id = $_GET['tag_ID'];
	}

	protected function refresh_value(): void {
		$this->value = get_term_meta( $this->object_id, $this->get_id(), $this->is_single() );
	}

	protected function get_data_type(): string {
		return $this->DATA_TYPE;
	}

	public function get_parents(): array {
		$taxonomies = array();

		foreach ( $this->taxonomies as $taxonomy ) {
			$taxonomies[] = ( $taxonomy instanceof Taxonomy ) ? $taxonomy->get_id() : $taxonomy;
		}

		return $taxonomies;
	}
}
