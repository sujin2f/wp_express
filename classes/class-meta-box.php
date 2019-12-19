<?php
/**
 * Metabox Class
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @param   string $name The name of the componenet
 * @since   the beginning
 * @todo
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Fields\Abstract_Filed_Post_Meta;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;
use Sujin\Wordpress\WP_Express\Helpers\Trait_With_Argument;
use Sujin\Wordpress\WP_Express\Arguments\Argument_Meta_Box;
use WP_Post;

class Meta_Box extends Abstract_Component {
	use Trait_Multiton;
	use Trait_With_Argument;

	/**
	 * @var Post_Type[]
	 */
	public $post_types = array();

	/**
	 * @var Abstract_Filed_Post_Meta[]
	 */
	public $post_metas = array();

	protected function __construct( string $name ) {
		parent::__construct( $name );
		$this->argument = new Argument_Meta_Box();

		add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
	}

	public function append( Abstract_Filed_Post_Meta $post_meta ): self {
		$post_meta->append_to( $this );
		$this->post_metas[] = $post_meta;
		return $this;
	}

	public function append_to( Post_Type $post_type ): self {
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
			$this->argument->get( 'context' ),
			$this->argument->get( 'priority' ),
		);
	}

	public function show_meta_box(): void {
		$post_id = $_GET['post'] ?? null;

		?>
		<section class="<?php echo esc_attr( self::PREFIX ); ?> metabox">
			<?php
			$this->wp_nonce_field();
			foreach ( $this->post_metas as $post_meta ) {
				$post_meta->render_form( $post_id );
			}
			?>
		</section>
		<?php
	}
}
