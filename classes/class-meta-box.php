<?php
/**
 * Metabox Class
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @param   ?string $name The name of the componenet
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Component;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta_Component;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;
use WP_Post;
class Meta_Box extends Component {
	use Trait_Multiton;

	/**
	 * @var Post_Type[]
	 */
	public $post_types = array();

	/**
	 * @var Post_Meta_Component[]
	 */
	public $post_metas = array();

	protected function __construct( $name ) {
		parent::__construct( $name );
		add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
	}

	public function append( Post_Meta_Component $post_meta ): Meta_Box {
		$post_meta->append_to( $this );
		$this->post_metas[] = $post_meta;
		return $this;
	}

	public function append_to( Post_Type $post_type ): Meta_Box {
		$this->post_types[] = $post_type;
		return $this;
	}

	public function register_meta_box(): void {
		if ( empty( $this->post_types ) ) {
			return;
		}

		add_meta_box(
			$this->get_id(),
			$this->get_name(),
			array( $this, 'show_meta_box' ),
			array_map(
				function( $post_type ) {
					return $post_type->get_id();
				},
				$this->post_types,
			),
		);
	}

	public function show_meta_box(): void {
		$post_id = $_GET['post'] ?? null;

		?>
		<section class="<?php echo esc_attr( self::PREFIX ) ?> metabox">
			<?php
			wp_nonce_field( $this->get_id(), $this->get_id() . '_nonce' );

			foreach( $this->post_metas as $post_meta ) {
				$post_meta->render_form( $post_id );
			}
			?>
		</section>
		<?php
	}
}
