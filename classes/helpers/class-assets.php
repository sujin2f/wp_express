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
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @param   string|array $manifest
 * @param   ?string      $base_url
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers;

use Sujin\Wordpress\WP_Express\Arguments\Argument_Assets;
use Sujin\Wordpress\WP_Express\Helpers\Enums\Assets_Type;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;
use Sujin\Wordpress\WP_Express\Helpers\Trait_With_Arguments;
use InvalidArgumentException;

class Assets {
	use Trait_Multiton;
	use Trait_With_Arguments;

	/*
	 * @var array
	 */
	private $manifest;

	/*
	 * @var ?tring
	 */
	private $base_url;

	protected function __construct( $manifest, ?string $base_url = null ) {
		$this->manifest = $manifest;
		$this->base_url = $base_url ? $base_url . DIRECTORY_SEPARATOR : '';

		add_action( 'init', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function append( string $url ): self {
		if ( is_array( $this->manifest ) && array_key_exists( $url, $this->manifest ) ) {
			$url = $this->manifest[ $url ] ?? $url;
		}

		$type     = substr( $url, -3 ) === '.js' ? Assets_Type::script() : Assets_Type::style();
		$handle   = $this->get_assets_handle( $url );
		$argument = new Argument_Assets();
		$argument->set( 'url', $this->base_url . $url );
		$argument->set( 'type', $type );

		$this->arguments[ $handle ] = $argument;
		return $this;
	}

	public function register_assets() {
		foreach ( $this->arguments as $handle => $argument ) {
			// Script
			if ( Assets_Type::SCRIPT === $argument->get( 'type' )->case() ) {
				wp_register_script(
					$handle,
					$argument->get( 'url' ),
					$argument->get( 'depends' ),
					$argument->get( 'version' ),
					$argument->get( 'is_footer' ),
				);

				if ( ! empty( $argument->get( 'translation' ) ) ) {
					wp_localize_script(
						$handle,
						$argument->get( 'translation_key' ),
						$argument->get( 'translation' ),
					);
				}

				continue;
			}

			// Style
			wp_register_style(
				$handle,
				$argument->get( 'url' ),
				$argument->get( 'depends' ),
				$argument->get( 'version' ),
				$argument->get( 'is_footer' ),
			);
		}
	}

	/*
	 * Actions: Front pages
	 */
	public function wp_enqueue_scripts() {
		foreach ( $this->arguments as $handle => $argument ) {
			if ( $argument->get( 'is_admin' ) ) {
				continue;
			}

			if ( Assets_Type::SCRIPT === $argument->get( 'type' )->case() ) {
				wp_enqueue_script( $handle );
			} else {
				wp_enqueue_style( $handle );
			}
		}
	}

	/*
	 * Actions: Admin pages
	 */
	public function admin_enqueue_scripts() {
		foreach ( $this->arguments as $handle => $argument ) {
			if ( ! $argument->get( 'is_admin' ) ) {
				continue;
			}

			if ( Assets_Type::SCRIPT === $argument->get( 'type' )->case() ) {
				wp_enqueue_script( $handle );
			} else {
				wp_enqueue_style( $handle );
			}
		}
	}

	/*
	 * Get Unique handle
	 */
	private function get_assets_handle( string $url ): string {
		return md5( json_encode( $this->manifest ) ) . '-' . sanitize_title( basename( $url ) );
	}
}
