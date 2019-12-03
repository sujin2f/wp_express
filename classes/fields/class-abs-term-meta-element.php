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
	private $taxonomies = array();

	protected function __construct( string $name, array $attrs = array() ) {
		parent::__construct( $name, $attrs );
		add_action( 'init', array( $this, 'register_meta' ) );
	}

	public function attach_to( $taxonomy ): Abs_Term_Meta_Element {
		$this->taxonomies[] = $taxonomy;

		if ( $taxonomy instanceof Taxonomy ) {
			$taxonomy = $taxonomy->get_id();
		}
		add_action( $taxonomy . '_edit_form_fields', array( $this, 'render' ), 25 );
		add_action( 'edited_' . $taxonomy, array( $this, 'update' ), 25 );

		return $this;
	}

	public function update( int $term_id, $value = null ) {
		if ( ! $value ) {
			$value = $_POST[ $this->get_id() ];
		}
		update_term_meta( $term_id, $this->get_id(), $value );
	}

	public function register_meta() {
		$args = array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		);
		register_meta( 'term', $this->get_id(), $args );
	}

	protected function refresh_attributes( ?int $term_id = null ) {
		if ( empty( $term_id ) ) {
			if ( empty( $_GET['tag_ID'] ?? null ) ) {
				return;
			}
			$term_id = $_GET['tag_ID'];
		}
		$this->attributes['value'] = get_term_meta( $term_id, $this->get_id(), true );
	}

	protected function render_wrapper_open() {
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

	protected function render_wrapper_close() {
		echo '</td></tr>';
	}

	public function get_parents(): array {
		$taxonomies = array();

		foreach ( $this->taxonomies as $taxonomy ) {
			$taxonomies[] = ( $taxonomy instanceof Taxonomy ) ? $taxonomy->get_id() : $taxonomy;
		}

		return $taxonomies;
	}
}
