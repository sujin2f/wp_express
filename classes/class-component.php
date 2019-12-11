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

	protected function __construct( ?string $name = null ) {
		if ( $name ) {
			$this->name = $name;
			$this->id   = sanitize_title( $name );
		}
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
