<?php
/**
 * The base class inherited for all types
 * 알파요 오메가이니라
 *
 * @project WP Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Exceptions\Initialized_Exception;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}
// @codeCoverageIgnoreEnd

abstract class Abs_Base {
	protected const PREFIX = 'wp-express';

	// Single/Multiton container
	protected static $multiton_container  = array();
	protected static $singleton_container = null;

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
		add_action( 'init', array( $this, '_register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, '_wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, '_admin_enqueue_scripts' ) );
	}

	/*
	 * Supports both singleton and multiton patterns
	 * Without argument, this returns a singleton
	 */
	public static function get_instance( ...$args ): Abs_Base {
		$num_args = func_num_args();
		$caller   = get_called_class();
		$args     = func_get_args();

		// Singleton
		if ( 0 === $num_args ) {
			if ( is_null( self::$singleton_container ) ) {
				static::$singleton_container = new static( $caller );
			}
			return static::$singleton_container;
		}

		// Multiton
		$id  = $args[0];
		$key = md5( $id );
		if ( ! array_key_exists( $key, self::$multiton_container ) ) {
			static::$multiton_container[ $key ] = new static( ...$args );
		}
		return static::$multiton_container[ $key ];
	}

	public function add_script( string $url, bool $is_admin = false, bool $is_footer = false ): Abs_Base {
		$handle                    = $this->_get_assets_handle( $url );
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

	public function script_localize( string $name, array $translation_array ): Abs_Base {
		$translation = array(
			'translation'     => $translation_array,
			'translation-key' => $name,
		);

		$this->scripts[ $this->p_scripts ] = array_merge( $this->scripts[ $this->p_scripts ], $translation );

		return $this;
	}

	public function add_style( string $url, bool $is_admin = false, bool $is_footer = false ): Abs_Base {
		$handle                   = $this->_get_assets_handle( $url );
		$this->styles[ $handle ] = array(
			'url'       => $url,
			'is_admin'  => $is_admin,
			'is_footer' => $is_footer,
		);

		return $this;
	}

	public function _register_assets() {
		## Scripts
		foreach ( $this->scripts as $handle => $data ) {
			wp_register_script( $handle, $data['url'], null, $this->_get_filetime( $data['url'] ), $data['is_footer'] );

			if ( ! empty( $data['translation'] ) ) {
				wp_localize_script( $handle, $data['translation-key'], $data['translation'] );
			}
		}

		## Styles
		foreach ( $this->styles as $handle => $data ) {
			wp_register_style( $handle, $data['url'], null, $this->_get_filetime( $data['url'] ), $data['is_footer'] );
		}
	}

	/*
	 * Actions: Front pages
	 */
	public function _wp_enqueue_scripts() {
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
	public function _admin_enqueue_scripts() {
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
	private function _get_assets_handle( string $url ): string {
		return self::PREFIX . '-' . sanitize_title( basename( $url ) );
	}

	private function _get_filetime( string $url ): string {
		return filemtime( str_replace( get_option( 'home' ) . '/', ABSPATH, $url ) );
	}

	protected function render_admin_message( string $text, string $class = 'updated' ): Abs_Base {
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
