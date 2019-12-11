<?php
/**
 * Assets Helper
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @since   8.0.0
 * @todo    Make this as a general purpose
 */

namespace Sujin\Wordpress\WP_Express\Helpers;

use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;

class Assets {
	use Trait_Multiton;

	protected const PREFIX = 'wp-express';

	public $manifest = array();

	/*
	 * All types can load assets.
	 */
	private $scripts = array();
	private $styles  = array();

	protected function __construct( $manifest ) {
		if ( is_array( $manifest ) ) {
			$this->manifest = $manifest;
		}

		add_action( 'init', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function add_script( string $url, bool $is_admin = false, bool $is_footer = false ): Assets {
		if ( array_key_exists( $url, $manifest ) ) {
			$url = $manifest[ $url ];
		}

		$handle                   = $this->get_assets_handle( $url );
		$this->scripts[ $handle ] = array(
			'url'       => $url,
			'is_admin'  => $is_admin,
			'is_footer' => $is_footer,
		);

		return $this;
	}

	public function add_style( string $url, bool $is_admin = false, bool $is_footer = false ): Assets {
		$handle                   = $this->get_assets_handle( $url );
		$this->styles[ $handle ] = array(
			'url'       => $url,
			'is_admin'  => $is_admin,
			'is_footer' => $is_footer,
		);

		return $this;
	}

	public function script_localize( string $name, array $translation_array ): Assets {
		$last_index = count( $this->scripts ) - 1;
		if ( 0 > $last_index ) {
			return $this;
		}

		$translation = array(
			'translation'     => $translation_array,
			'translation-key' => $name,
		);

		$this->scripts[ $last_index ] = array_merge( $this->scripts[ $last_index ], $translation );

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
}
