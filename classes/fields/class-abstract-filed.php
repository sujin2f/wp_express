<?php
/**
 * The base class inherited for all field types
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @param   string $name The name of the componenet
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Abstract_Component;
use Sujin\Wordpress\WP_Express\Arguments\Argument_Field;
use Sujin\Wordpress\WP_Express\Helpers\Assets;
use Sujin\Wordpress\WP_Express\Helpers\Trait_With_Argument;

abstract class Abstract_Filed extends Abstract_Component {
	use Trait_With_Argument;

	/**
	 * Field value
	 *
	 * @var any
	 */
	protected $value;

	/**
	 * Post/ Term id
	 * @var integer
	 */
	protected $wp_object_id;

	/**
	 * @var Assets
	 */
	protected static $assets;

	/**
	 * @var array
	 */
	protected static $manifest;

	protected function __construct( string $name ) {
		parent::__construct( $name );
		$this->argument = new Argument_Field();
		$this->set_assets();
		$this->init();
	}

	private function set_assets(): void {
		if ( ! empty( self::$assets ) ) {
			return;
		}

		$manifest = WP_EXPRESS_ASSET_DIR . DIRECTORY_SEPARATOR . 'manifest.json';

		if ( ! file_exists( $manifest ) ) {
			throw new RuntimeException( '😡 manifest.json is missing.' );
		}

		$manifest = file_get_contents( $manifest );
		$manifest = json_decode( $manifest, true );

		self::$assets = Assets::get_instance( $manifest, WP_EXPRESS_ASSET_URL );
		self::$assets
			->append( 'style.scss' )
			->is_admin( true )
			->append( 'app.js' )
			->is_admin( true );
	}

	/**
	 * Get value
	 */
	public function get( ?int $id = null ) {
		$this->refresh_id( $id );
		$this->refresh_value();
		return $this->value;
	}

	/**
	 * Render the form
	 */
	public function render_form( ?int $id = null ): void {
		if ( false === $this->is_available() ) {
			return;
		}

		$this->refresh_id( $id );
		$this->refresh_value();

		$this->render_form_wrapper_open();
		$this->render_form_field();
		$this->render_form_wrapper_close();
	}

	/**
	 * For types which has options, when they don't have any option, simply disable it.
	 */
	protected function is_available(): bool {
		return true;
	}

	protected function get_called_class(): string {
		$class = explode( '\\', get_called_class() );
		return strtolower( array_pop( $class ) );
	}

	/**
	 * Get data type
	 * https://developer.wordpress.org/reference/functions/register_meta/
	 *
	 * @return string 'string', 'boolean', 'integer', 'number', 'array', or 'object'
	 * @todo   Make it as Enum (in Abstract_Argument)
	 */
	protected abstract function get_data_type(): string;

	protected function is_single(): bool {
		return $this->argument->get( 'single' );
	}

	public abstract function update( ?int $id = null, $value = null ): void;
	protected abstract function init(): void;
	protected abstract function refresh_id( ?int $id = null ): void;
	protected abstract function refresh_value(): void;
	protected abstract function render_form_wrapper_open(): void;
	protected abstract function render_form_wrapper_close(): void;
	protected abstract function render_form_field(): void;
}
