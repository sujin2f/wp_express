<?php
/**
 * The base class inherited for all types
 * 알파요 오메가이니라
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Helpers\Interface_Identifier;

/**
 * The base class inherited for all types
 * 알파요 오메가이니라
 *
 * The inherited class has unique identifier and name.
 * The id is usually from the name.
 */
abstract class Abstract_Component implements Interface_Identifier {
	protected const PREFIX = 'wp-express';

	/**
	 * Unique Identifier
	 *
	 * @var string
	 */
	private $object_id;

	/**
	 * Get Unique Identifier
	 *
	 * @return string
	 */
	public function get_id(): string {
		return $this->object_id;
	}

	/**
	 * Name of the object
	 * Usually contains the instance name:
	 * i.g. Some_Component::get_instance( 'Instance Name' );
	 *
	 * @var string
	 */
	private $object_name;

	/**
	 * Get object name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->object_name;
	}

	/**
	 * Get object name
	 *
	 * @param string $name The name of the componenet.
	 */
	protected function __construct( string $name ) {
		$this->object_name = $name;
		$this->object_id   = sanitize_title( $name );
	}

	/**
	 * Show admin message (updated, error)
	 *
	 * @param  string $text  The message to show.
	 * @param  string $class HTML class attribute.
	 * @return Abstract_Component
	 */
	protected function render_admin_message( string $text, string $class = 'updated' ): self {
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

	/**
	 * Print nonce field
	 */
	protected function wp_nonce_field(): void {
		wp_nonce_field( $this->get_id(), $this->get_id() . '_nonce' );
	}
}
