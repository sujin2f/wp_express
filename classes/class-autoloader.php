<?php
/**
 * Autoloader
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @param   string $namespace
 * @param   string $base_dir
 */

namespace Sujin\Wordpress\WP_Express;

class Autoloader {
	/**
	 * Base namespace
	 *
	 * @var string
	 */
	private $namespace;

	/**
	 * Base directory
	 *
	 * @var string
	 */
	private $base_dir;

	public function __construct( string $namespace, string $base_dir ) {
		$this->namespace = $namespace;
		$this->base_dir  = $base_dir;
	}

	/**
	 * Returns the target paths
	 *
	 * @return array sting extension and array path
	 */
	private function get_class_path( string $class_name ): ?array {
		if ( stripos( $class_name, $this->namespace ) === false ) {
			return null;
		}

		$path         = array(
			'extension' => '.php',
			// Delete Namespace and divide
			'path'      => explode( '\\', str_replace( $this->namespace . '\\', '', $class_name ) ),
		);
		$path['path'] = array_map( array( $this, 'map_convert_path' ), $path['path'] );
		array_unshift( $path['path'], $this->base_dir );

		return $path;
	}

	/**
	 * Entry point
	 */
	public function load_class_file( string $class_name ): void {
		$path = $this->get_class_path( $class_name );

		if ( ! $path ) {
			return;
		}

		$index = implode( DIRECTORY_SEPARATOR, $path['path'] ) . DIRECTORY_SEPARATOR . 'index' . $path['extension'];
		$file  = array_pop( $path['path'] );
		$file  = implode( DIRECTORY_SEPARATOR, $path['path'] ) . DIRECTORY_SEPARATOR . 'class-' . $file . $path['extension'];

		if ( is_readable( $file ) ) {
			include_once( $file );
			return;
		}

		if ( is_readable( $index ) ) {
			include_once( $index );
			return;
		}
	}

	/**
	 * Callback method of path conversion
	 */
	private function map_convert_path( string $string ): string {
		$segments = array();

		preg_match_all( '/((?:^|[A-Z])[A-Za-z0-9]+)/', $string, $matches );

		return strtolower( implode( '-', $matches[0] ) );
	}

	/**
	 * Entry point from outside
	 */
	public function register(): void {
		spl_autoload_register( array( $this, 'load_class_file' ) );
	}
}
