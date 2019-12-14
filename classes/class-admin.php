<?php
/**
 * Creates Admin Page
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @param   string $name The name of the componenet
 * @since   the beginning
 * @todo    render_screen_options()
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Helpers\{
	Trait_Multiton,
	Trait_With_Argument,
};
use Sujin\Wordpress\WP_Express\Helpers\Enums\Admin_Position;
use Sujin\Wordpress\WP_Express\Arguments\Argument_Admin;

class Admin extends Abstract_Component {
	use Trait_Multiton;
	use Trait_With_Argument;

	private $url;

	protected function __construct( string $name ) {
		parent::__construct( $name );
		$this->argument = new Argument_Admin();

		add_action( 'network_admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'plugin_action_links', array( $this, 'plugin_action_links' ), 15, 3 );
	}

	public function append( Setting $setting ): self {
		$setting->append_to( $this );
		return $this;
	}

	/*
	 * @called-by https://developer.wordpress.org/reference/hooks/admin_menu/
	 * @called-by https://developer.wordpress.org/reference/hooks/network_admin_menu/
	 */
	public function register_menu(): void {
		$position = $this->argument->get( 'position' );

		if ( is_string( $position ) && $this->register_menu_by_position() ) {
			return;
		}

		// When the position is WP Express class
		if ( is_object( $position ) && $this->register_menu_in_express_class() ) {
			return;
		}

		// When the position is numeric
		if ( is_numeric( $position ) ) {
			$this->register_menu_in_numeric_position();
			return;
		}

		// When the position is a menu Name
		if ( $this->register_menu_in_string_position() ) {
			return;
		}

		// To root position
		$this->url = admin_url( 'admin.php?page=' . $this->get_id() );
		$slug      = add_menu_page( ...$this->get_menu_args() );
		// add_action( 'load-' . $slug, array( $this, 'render_screen_options' ) );
	}

	/*
	 * @called-by self::register_menu()
	 */
	private function register_menu_by_position(): bool {
		$position = $this->argument->get( 'position' );
		$position = Admin_Position::get_parent_file_name( strtolower( $position ) );

		if ( is_null( $position ) ) {
			return false;
		}

		$this->url = add_query_arg( 'page', $this->get_id(), admin_url( $position ) );
		$slug      = add_submenu_page( $position, ...$this->get_menu_args() );
		// add_action( 'load-' . $slug, array( $this, 'render_screen_options' ) );
		return true;
	}

	/*
	 * @called-by self::register_menu()
	 */
	private function register_menu_in_express_class(): bool {
		$position = $this->argument->get( 'position' );

		if ( $position instanceof Post_Type ) {
			$this->url = admin_url( 'edit.php?post_type=' . $position->get_id() . '&page=' . $this->get_id() );
		} elseif ( $position instanceof Admin ) {
			$this->url = admin_url( 'options-general.php?page=' . $position->get_id() );
		} else {
			return false;
		}

		$slug = add_submenu_page( $position->get_id(), ...$this->get_menu_args() );
		// add_action( 'load-' . $page_slug, array( $this, 'render_screen_options' ) );

		return true;
	}

	/*
	 * @called-by self::register_menu()
	 */
	private function register_menu_in_numeric_position(): void {
		global $menu;
		$position = $this->argument->get( 'position' );

		if ( isset( $menu[ $position ] ) ) { // To existing position
			$parent_url = $menu[ $position ][2];
			$this->url  = add_query_arg( 'page', $this->get_id(), $parent_url );
			$page_slug  = add_submenu_page( $parent_url, ...$this->get_menu_args() );
			return;
		}

		$args      = $this->get_menu_args();
		$args[]    = $this->argument->get( 'icon' );
		$args[]    = $position;
		$page_slug = add_menu_page( ...$args );

		// add_action( 'load-' . $page_slug, array( $this, 'render_screen_options' ) );
	}

	/*
	 * @called-by self::register_menu()
	 */
	private function register_menu_in_string_position(): bool {
		global $menu;
		$position = $this->argument->get( 'position' );

		if ( empty( $menu ) ) {
			return false;
		}

		foreach ( $menu as $menu_item ) {
			if ( $position !== $menu_item[0] ) {
				continue;
			}

			$parent_url = $menu_item[2];
			$this->url  = add_query_arg( 'page', $this->get_id(), $parent_url );
			$page_slug  = add_submenu_page( $parent_url, ...$this->get_menu_args() );
			// add_action( 'load-' . $page_slug, array( $this, 'render_screen_options' ) );
			return true;
		}

		return false;
	}

	/*
	 * @called-by https://developer.wordpress.org/reference/hooks/plugin_action_links/
	 */
	public function plugin_action_links( array $actions, string $_, array $plugin_data ): array {
		$plugin = $this->argument->get( 'plugin' );

		if ( empty( $plugin ) ) {
			return $actions;
		}

		if ( sanitize_title( $plugin ) == sanitize_title( $plugin_data['Name'] ) ) {
			$actions['setting'] = sprintf(
				'<a href="%s"><span class="dashicons-before dashicons-admin-generic"></span> Setting</a>',
				$this->url
			);
		}

		return $actions;
	}

	public function render(): void {
		?>
		<div
			class="<?php echo esc_attr( self::PREFIX ); ?> admin wrap"
			id="<?php echo esc_attr( self::PREFIX ); ?>-admin-<?php echo esc_attr( $this->get_id() ); ?>"
		>
			<h2 class="page-title">
				<span class="dashicons <?php echo esc_attr( $this->argument->get( 'icon' ) ); ?>"></span>
				<?php echo esc_html( $this->get_name() ); ?>
			</h2>

			<form method="post" action="options.php">
				<?php
				settings_fields( $this->get_id() );
				do_settings_sections( $this->get_id() );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	private function get_menu_args(): array {
		return array(
			$this->get_name(),
			$this->get_name(),
			$this->argument->get( 'capability' ),
			$this->get_id(),
			array( $this, 'render' ),
		);
	}
}
