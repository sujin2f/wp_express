<?php
/**
 * Creates Admin Page
 * 무엇이든 만들지어다
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @param   ?string $name The name of the componenet
 * @since   the beginning
 * @todo    render_screen_options()
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Setting;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;
use Sujin\Wordpress\WP_Express\Helpers\Enums\Admin_Position;

class Admin extends Component {
	use Trait_Multiton;

	private $admin_url;

	private const POSITION   = 'position';
	private const ICON       = 'icon';
	private const CAPABILITY = 'capability';
	private const PLUGIN     = 'plugin';

	private $position   = 'settings';
	private $icon       = 'dashicons-admin-generic';
	private $capability = 'manage_options';
	private $plugin     = null;

	protected function __construct( ?string $name = null ) {
		parent::__construct( $name );

		add_action( 'network_admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'plugin_action_links', array( $this, 'plugin_action_links' ), 15, 3 );
	}

	public function __call( string $name, array $arguments ) {
		switch ( strtolower( $name ) ) {
			case self::POSITION:
			case self::ICON:
			case self::CAPABILITY:
			case self::PLUGIN:
				if ( empty( $arguments ) ) {
					return $this->{$name};
				}

				$this->{$name} = $arguments[0];
				break;
		}

		return $this;
	}

	public function append( Setting $setting ): Admin {
		$setting->admin_page( $this );
		return $this;
	}

	# ACTION admin_menu, network_admin_menu
	public function register_admin_menu() {
		$parent_slug = '';

		if ( is_string( $this->position ) && $this->register_admin_menu_by_position() ) {
			return;
		}

		## When the position is WP Express class
		if ( is_object( $this->position ) && ( $this->position instanceof Admin || $this->position instanceof Post_Type ) ) {
			$this->register_admin_menu_in_express_class();
			return;
		}

		## When the position is numeric
		if ( is_numeric( $this->position ) ) {
			$this->register_admin_menu_in_numeric_position();
			return;
		}

		## When the position is a menu Name
		if ( $this->register_admin_menu_in_string_position() ) {
			return;
		}

		## To root position
		$this->admin_url = admin_url( 'admin.php?page=' . $this->get_id() );
		$page_slug        = add_menu_page( ...$this->get_menu_args() );
		add_action( 'load-' . $page_slug, array( $this, 'render_screen_options' ) );
	}

	# Excecuted by: register_admin_menu()
	private function register_admin_menu_by_position(): bool {
		$parent_slug = Admin_Position::get_parent_slug( strtolower( $this->position ) );

		if ( is_null( $parent_slug ) ) {
			return false;
		}

		$this->admin_url = add_query_arg( 'page', $this->get_id(), admin_url( $parent_slug ) );
		$page_slug       = add_submenu_page( $parent_slug, ...$this->get_menu_args() );
		add_action( 'load-' . $page_slug, array( $this, 'render_screen_options' ) );
		return true;
	}

	# Excecuted by: register_admin_menu()
	private function register_admin_menu_in_express_class() {
		if ( $this->position instanceof Post_Type ) {
			$this->admin_url = admin_url( 'edit.php?post_type=' . $this->position->get_id() . '&page=' . $this->get_id() );
		}
		$page_slug = add_submenu_page( $this->position->get_id(), ...$this->get_menu_args() );
		add_action( 'load-' . $page_slug, array( $this, 'render_screen_options' ) );
	}

	# Excecuted by: register_admin_menu()
	private function register_admin_menu_in_numeric_position() {
		global $menu;
		if ( isset( $menu[ $this->position ] ) ) { ## To existing position
			$parent_url       = $menu[ $this->position ][2];
			$this->admin_url = add_query_arg( 'page', $this->get_id(), $parent_url );
			$page_slug        = add_submenu_page( $parent_url, ...$this->get_menu_args() );

		} else { ## To new position
			$args      = $this->get_menu_args();
			$args[]    = $this->icon;
			$args[]    = $this->position;
			$page_slug = add_menu_page( ...$args );
		}

		add_action( 'load-' . $page_slug, array( $this, 'render_screen_options' ) );
	}

	# Excecuted by: register_admin_menu()
	private function register_admin_menu_in_string_position(): bool {
		global $menu;

		if ( empty( $menu ) ) {
			return false;
		}

		foreach ( $menu as $menu_item ) {
			if ( $this->position === $menu_item[0] ) {
				$parent_url       = $menu_item[2];
				$this->admin_url = add_query_arg( 'page', $this->get_id(), $parent_url );
				$page_slug        = add_submenu_page( $parent_url, ...$this->get_menu_args() );
				add_action( 'load-' . $page_slug, array( $this, 'render_screen_options' ) );
				return true;
			}
		}

		return false;
	}

	# ACTION plugin_action_links
	public function plugin_action_links( array $actions, string $_, array $plugin_data ): array {
		if ( empty( $this->plugin ) ) {
			return $actions;
		}

		if ( sanitize_title( $this->plugin ) == sanitize_title( $plugin_data['Name'] ) ) {
			$actions['setting'] = sprintf(
				'<a href="%s"><span class="dashicons-before dashicons-admin-generic"></span> Setting</a>',
				$this->admin_url
			);
		}

		return $actions;
	}

	public function render() {
		?>
		<div
			class="<?php echo esc_attr( self::PREFIX ); ?> admin wrap"
			id="<?php echo esc_attr( self::PREFIX ); ?>-admin-<?php echo esc_attr( $this->get_id() ); ?>"
		>
			<h2 class="page-title <?php echo esc_attr( self::PREFIX ); ?>">
				<span class="dashicons <?php echo esc_attr( $this->icon ); ?>"></span>
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
			$this->capability,
			$this->get_id(),
			array( $this, 'render' ),
		);
	}
}
