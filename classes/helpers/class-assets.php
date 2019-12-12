<?php
/**
 * Assets Helper
 *
 * If you pass manifest array as the parameter, 
 * this class will automatically read the destination file
 * 
 * ```
 * $asset = Assets::get_instance( array( 'app.js' => 'app.9sdf83jdFs09.js' ), 'http://your.site/assets/' );
 * $asset->add_script( 'app.js' ); // will load http://your.site/assets/app.9sdf83jdFs09.js
 * ```
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @since   4.0.0
 * @param   ?manifest $name The name of the componenet
 * @todo    defendancy & version
 */

namespace Sujin\Wordpress\WP_Express\Helpers;

use Sujin\Wordpress\WP_Express\Helpers\Enums\Assets_Type;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;
use Sujin\Wordpress\WP_Express\Types\Assets_Argument;
use InvalidArgumentException;

class Assets {
	use Trait_Multiton;

	/*
	 * @var array
	 */
	private $manifest;

	/*
	 * @var ?tring
	 */
	private $base_url;

	/*
	 * @var Assets_Argument[]
	 */
	private $assets = array();

	protected function __construct( $manifest, ?string $base_url = null ) {
		$this->manifest = $manifest;
		$this->base_url = $base_url;

		add_action( 'init', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Sets Assets_Argument properties
	 * @throws InvalidArgumentException
	 * @return self
	 */
	public function __call( string $key, array $arguments ): self {
		if ( empty( $arguments ) ) {
			throw new InvalidArgumentException( '😡 No getter supported.' );
		}

		$last_index = array_key_last( $this->assets );
		
		if ( ! $last_index ) {
			throw new InvalidArgumentException( '😡 No assets were assigned.' );
		}

		if ( ! property_exists( $this->assets[ $last_index ], $key ) ) {
			throw new InvalidArgumentException( '😡 Asset property does not exist.' );
		}

		$this->assets[ $last_index ]->{$key} = $arguments[0];
		return $this;
	}

	public function add_script( string $url ): self {
		if ( is_array( $this->manifest ) && array_key_exists( $url, $this->manifest ) ) {
			$url = $this->manifest[ $url ] ?? $url;
		}

		$handle          = $this->get_assets_handle( $url );
		$args            = new Assets_Argument();
		$args->type      = Assets_Type::script();
		$args->url       = $this->base_url . $url;

		$this->assets[ $handle ] = $args;

		return $this;
	}

	public function add_style( string $url ): self {
		if ( is_array( $this->manifest ) && array_key_exists( $url, $this->manifest ) ) {
			$url = $this->manifest[ $url ] ?? $url;
		}

		$handle          = $this->get_assets_handle( $url );
		$args            = new Assets_Argument();
		$args->type      = Assets_Type::style();
		$args->url       = $this->base_url . $url;

		$this->assets[ $handle ] = $args;

		return $this;
	}

	public function register_assets() {
		## Scripts
		foreach ( $this->get_scripts() as $handle => $args ) {
			wp_register_script( $handle, $args->url, $args->depends, $args->version, $args->is_footer );

			if ( ! empty( $args->translation ) ) {
				wp_localize_script( $handle, $args->translation_key, $args->translation );
			}
		}

		## Styles
		foreach ( $this->get_styles() as $handle => $args ) {
			wp_register_style( $handle, $args->url, $args->depends, $args->version, $args->is_footer );
		}
	}

	/*
	 * Actions: Front pages
	 */
	public function wp_enqueue_scripts() {
		foreach ( $this->get_scripts() as $handle => $args ) {
			if ( ! $args->is_admin ) {
				wp_enqueue_script( $handle );
			}
		}

		foreach ( $this->get_styles() as $handle => $args ) {
			if ( ! $args->is_admin ) {
				wp_enqueue_style( $handle );
			}
		}
	}

	/*
	 * Actions: Admin pages
	 */
	public function admin_enqueue_scripts() {
		foreach ( $this->get_scripts() as $handle => $args ) {
			if ( $args->is_admin ) {
				wp_enqueue_script( $handle );
			}
		}

		foreach ( $this->get_styles() as $handle => $args ) {
			if ( $args->is_admin ) {
				wp_enqueue_style( $handle );
			}
		}
	}

	/*
	 * Get Unique handle
	 */
	private function get_assets_handle( string $url ): string {
		return md5( $this->manifest ) . '-' . sanitize_title( basename( $url ) );
	}

	/*
	 * Get scripts from self::$assets
	 * @return Assets_Argument[]
	 */
	private function get_scripts(): array {
		return array_filter(
			$this->assets,
			function ( Assets_Argument $asset ) {
				return Assets_Type::SCRIPT === $asset->type->case();
			},
		);
	}

	/*
	 * Get styles from self::$assets
	 * @return Assets_Argument[]
	 */
	private function get_styles(): array {
		return array_filter(
			$this->assets,
			function ( Assets_Argument $asset ) {
				return Assets_Type::STYLE === $asset->type->case();
			},
		);
	}
}
