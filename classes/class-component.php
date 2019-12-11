<?php
/**
 * The base class inherited for all types
 * 알파요 오메가이니라
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @param   ?string $name The name of the componenet
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Exceptions\Initialized_Exception;

abstract class Component {
	protected const PREFIX = 'wp-express';

	// Single/Multiton container
	protected static $multiton_container  = array();
	protected static $singleton_container = null;

	protected static $manifest;

	/*
	 * All types have its own unique ID
	 */
	private $id = null;
	public function get_id(): string {
		if ( is_null( $this->id ) ) {
			throw new Initialized_Exception();
		}
		return $this->id;
	}

	/*
	 * All types have its name
	 */
	private $name = null;
	public function get_name(): string {
		if ( is_null( $this->name ) ) {
			throw new Initialized_Exception();
		}
		return $this->name;
	}

	/*
	 * All types can load assets. $_p_scripts is the pointer of the $_scripts
	 */
	private $scripts   = array();
	private $p_scripts = null;
	private $styles    = array();

	protected function __construct( ?string $name = null ) {
		$this->name = $name;
		$this->id   = sanitize_title( $name );

		## Assets
		add_action( 'init', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		$this->update_manifest();
	}

	private function update_manifest(): void {
		if ( ! empty( self::$manifest ) ) {
			return;
		}

		$manifest = WP_EXPRESS_ASSET_DIR . '/manifest.json';

		if ( ! file_exists( $manifest ) ) {
			return;
		}

		$manifest = file_get_contents( $manifest );
		self::$manifest = json_decode( $manifest, true );

	}

	/*
	 * Supports both singleton and multiton patterns
	 * Without argument, this returns a singleton
	 */
	public static function get_instance( ...$args ): Component {
		$num_args = func_num_args();
		$caller   = get_called_class();
		$args     = func_get_args();

		// Singleton
		if ( 0 === $num_args ) {
			if ( is_null( static::$singleton_container ) ) {
				static::$singleton_container = new static( $caller );
			}
			return static::$singleton_container;
		}

		// Multiton
		$id  = $args[0];
		$key = md5( $id );
		if ( ! array_key_exists( $key, static::$multiton_container ) ) {
			static::$multiton_container[ $key ] = new static( ...$args );
		}
		return static::$multiton_container[ $key ];
	}

	/*
	 * Gets multiton instance
	 */
	public static function get_instances(): array {
		return static::$multiton_container;
	}

	public function add_script( string $url, bool $is_admin = false, bool $is_footer = false ): Component {
		$handle                    = $this->get_assets_handle( $url );
		$attr                      = $this->scripts[ $handle ] ?? array();
		$attr_new                  = array(
			'url'       => $url,
			'is_admin'  => $is_admin,
			'is_footer' => $is_footer,
		);
		$this->scripts[ $handle ] = array_merge( $attr, $attr_new );
		$this->p_scripts          = $handle;

		return $this;
	}

	public function script_localize( string $name, array $translation_array ): Component {
		$translation = array(
			'translation'     => $translation_array,
			'translation-key' => $name,
		);

		$this->scripts[ $this->p_scripts ] = array_merge( $this->scripts[ $this->p_scripts ], $translation );

		return $this;
	}

	public function add_style( string $url, bool $is_admin = false, bool $is_footer = false ): Component {
		$handle                   = $this->get_assets_handle( $url );
		$this->styles[ $handle ] = array(
			'url'       => $url,
			'is_admin'  => $is_admin,
			'is_footer' => $is_footer,
		);

		return $this;
	}

	public function register_assets() {
		## Scripts
		foreach ( $this->scripts as $handle => $data ) {
			wp_register_script( $handle, $data['url'], null, null, $data['is_footer'] );

			if ( ! empty( $data['translation'] ) ) {
				wp_localize_script( $handle, $data['translation-key'], $data['translation'] );
			}
		}

		## Styles
		foreach ( $this->styles as $handle => $data ) {
			wp_register_style( $handle, $data['url'], null, null, $data['is_footer'] );
		}
	}

	/*
	 * Actions: Front pages
	 */
	public function wp_enqueue_scripts() {
		foreach ( $this->scripts as $handle => $data ) {
			if ( ! $data['is_admin'] ) {
				wp_enqueue_script( $handle );
			}
		}

		foreach ( $this->styles as $handle => $data ) {
			if ( ! $data['is_admin'] ) {
				wp_enqueue_style( $handle );
			}
		}
	}

	/*
	 * Actions: Admin pages
	 */
	public function admin_enqueue_scripts() {
		foreach ( $this->scripts as $handle => $data ) {
			if ( $data['is_admin'] ) {
				wp_enqueue_script( $handle );
			}
		}

		foreach ( $this->styles as $handle => $data ) {
			if ( $data['is_admin'] ) {
				wp_enqueue_style( $handle );
			}
		}
	}

	/*
	 * Get Unique handle
	 */
	private function get_assets_handle( string $url ): string {
		return self::PREFIX . '-' . sanitize_title( basename( $url ) );
	}

	protected function render_admin_message( string $text, string $class = 'updated' ): Component {
		if ( ! is_admin() ) {
			return $this;
		}

		?>
		<div id="message" class="<?php echo esc_attr( $class ); ?>">
			<p><?php echo esc_html( $text ); ?></p>
		</div>
		<?php

		return $this;
	}
}
