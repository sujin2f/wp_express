<?php
/**
 * Creates Admin Page
 * 무엇이든 만들지어다
 *
 * @project WP Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 * @todo    None Dashicon
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Setting;
use Sujin\Wordpress\WP_Express\Enum\Options_Admin;
use Sujin\Wordpress\WP_Express\Enum\Options_Admin_Position;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Admin extends Abs_Base {
	protected static $_multiton_container  = array();
	protected static $_singleton_container = null;

	protected $_admin_url;
	protected $_position   = 'settings';
	protected $_icon       = 'dashicons-admin-generic';
	protected $_capability = 'manage_options';
	protected $_plugin     = null;

	protected function __construct( string $name ) {
		parent::__construct( $name );

		$this->allowed_option = new Options_Admin();

		add_action( 'network_admin_menu', array( $this, '_register_admin_menu' ) );
		add_action( 'admin_menu', array( $this, '_register_admin_menu' ) );
		add_action( 'plugin_action_links', array( $this, '_plugin_action_links' ), 15, 3 );
	}

	// Add Setting section into this
	public function add( Setting $setting ): Admin {
		$key = $setting->_get_filter_key();
		remove_all_filters( $key );
		add_filter( $key, function() { return $this->get_id(); } );
		return $this;
	}

	// Attach this into another Admin instance
	public function attach_to( Admin $admin ): Admin {
		$this->_position = $admin;
		return $this;
	}

	// Start registering in the admin menu.
	// ACTION admin_menu, network_admin_menu
	public function _register_admin_menu() {
		$parent_slug = '';

		if ( is_string( $this->_position ) && $this->_register_admin_menu_by_position() ) {
			return;
		}

		## When the position is WP Express class
		if ( is_object( $this->_position ) && ( $this->_position instanceof Admin || $this->_position instanceof Post_Type ) ) {
			$this->_register_admin_menu_in_express_class();
			return;
		}

		## When the position is numeric
		if ( is_numeric( $this->_position ) ) {
			$this->_register_admin_menu_in_numeric_position();
			return;
		}

		## When the position is a menu Name
		if ( $this->_register_admin_menu_in_string_position() ) {
			return;
		}

		## To root position
		$this->_admin_url = admin_url( 'admin.php?page=' . $this->get_id() );
		$page_slug        = add_menu_page( ...$this->_get_menu_args() );
		add_action( 'load-' . $page_slug, array( $this, '_render_screen_options' ) );
	}

	// Excecuted by: _register_admin_menu()
	private function _register_admin_menu_by_position(): bool {
		$parent_slug = null;

		switch ( strtolower( $this->_position ) ) {
			case Options_Admin_Position::OPTION:
			case Options_Admin_Position::SETTINGS:
				$parent_slug = 'options-general.php';
				break;

			case Options_Admin_Position::TOOLS:
				$parent_slug = 'tools.php';
				break;

			case Options_Admin_Position::USERS:
				$parent_slug = 'users.php';
				break;

			case Options_Admin_Position::PLUGINS:
				$parent_slug = 'plugins.php';
				break;

			case Options_Admin_Position::COMMENTS:
				$parent_slug = 'edit-comments.php';
				break;

			case Options_Admin_Position::PAGES:
				$parent_slug = 'edit.php?post_type=page';
				break;

			case Options_Admin_Position::POSTS:
				$parent_slug = 'edit.php';
				break;

			case Options_Admin_Position::MEDIA:
				$parent_slug = 'upload.php';
				break;

			case Options_Admin_Position::DASHBOARD:
				$parent_slug = 'index.php';
				break;

			case Options_Admin_Position::APPEARANCE:
				$parent_slug = 'themes.php';
				break;
		}

		if ( is_null( $parent_slug ) ) {
			return false;
		}

		$this->_admin_url = add_query_arg( 'page', $this->get_id(), admin_url( $parent_slug ) );
		$page_slug        = add_submenu_page( $parent_slug, ...$this->_get_menu_args() );
		add_action( 'load-' . $page_slug, array( $this, '_render_screen_options' ) );
		return true;
	}

	// Excecuted by: _register_admin_menu()
	private function _register_admin_menu_in_express_class() {
		if ( $this->_position instanceof Post_Type ) {
			$this->_admin_url = admin_url( 'edit.php?post_type=' . $this->_position->get_id() . '&page=' . $this->get_id() );
		}
		$page_slug = add_submenu_page( $this->_position->get_id(), ...$this->_get_menu_args() );
		add_action( 'load-' . $page_slug, array( $this, '_render_screen_options' ) );
	}

	// Excecuted by: _register_admin_menu()
	private function _register_admin_menu_in_numeric_position() {
		global $menu;
		$args = $this->_get_menu_args();

		if ( isset( $menu[ $this->_position ] ) ) { ## To existing position
			$parent_url       = $menu[ $this->_position ][2];
			$this->_admin_url = add_query_arg( 'page', $this->get_id(), $parent_url );
			$page_slug        = add_submenu_page( $parent_url, ...$args );

		} else { ## To new position
			$args[]    = $this->_position;
			$page_slug = add_menu_page( ...$args );
		}

		add_action( 'load-' . $page_slug, array( $this, '_render_screen_options' ) );
	}

	// Excecuted by: _register_admin_menu()
	private function _register_admin_menu_in_string_position(): bool {
		global $menu;

		if ( empty( $menu ) ) {
			return false;
		}

		foreach ( $menu as $menu_item ) {
			if ( $this->_position === $menu_item[0] ) {
				$parent_url       = $menu_item[2];
				$this->_admin_url = add_query_arg( 'page', $this->get_id(), $parent_url );
				$page_slug        = add_submenu_page( $parent_url, ...$this->_get_menu_args() );
				add_action( 'load-' . $page_slug, array( $this, '_render_screen_options' ) );
				return true;
			}
		}

		return false;
	}

	// Put the link to this admin page in the Plugins page
	// ACTION plugin_action_links
	public function _plugin_action_links( array $actions, string $_, array $plugin_data ): array {
		if ( empty( $this->_plugin ) ) {
			return $actions;
		}

		if ( sanitize_title( $this->_plugin ) == sanitize_title( $plugin_data['Name'] ) ) {
			$actions['setting'] = sprintf(
				'<a href="%s"><span class="dashicons-before dashicons-admin-generic"></span> Setting</a>',
				$this->_admin_url
			);
		}

		return $actions;
	}

	// Render
	public function _render() {
		?>
		<div
			class="<?php echo esc_attr( self::PREFIX ); ?> admin wrap"
			id="<?php echo esc_attr( self::PREFIX ); ?>-admin-<?php echo esc_attr( $this->get_id() ); ?>"
		>
			<h2 class="page-title <?php echo esc_attr( self::PREFIX ); ?>">
				<span class="dashicons <?php echo esc_attr( $this->_icon ); ?>"></span>
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

	// TODO
	public function _render_screen_options() {}

	// HELPER
	private function _get_menu_args(): array {
		return array(
			$this->get_name(),
			$this->get_name(),
			$this->_capability,
			$this->get_id(),
			array( $this, '_render' ),
			$this->_icon,
		);
	}
}
