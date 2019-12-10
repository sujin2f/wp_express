<?php
/**
 * Interface for Fields
 *
 * @package WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Setting;
use Sujin\Wordpress\WP_Express\Admin;

abstract class Abs_Setting_Element extends Abs_Base_Element {
	/**
	 * @var Abs_Post_Meta_Element[]
	 */
	protected static $multiton_container  = array();

	/**
	 * @var Setting
	 */
	private $setting;

	public function attach_to( Setting $setting ) {
		$this->setting        = $setting;
		$this->option->legend = $setting->get_name();
	}

	public function update( ?int $post_id = null, $value = null ): void {
	}

	protected function init(): void {
		add_action( 'admin_init', array( $this, 'add_settings_field' ) );
	}

	public function get( ?int $_ = null ) {
		return get_option( $this->get_id() );
	}

	public function add_settings_field() {
		if ( empty( $this->setting ) || empty( $this->setting->admin_page() ) ) {
			return;
		}

		$parent_id =
			( $this->setting->admin_page() instanceof Admin )
			? $this->setting->admin_page()->get_id()
			: $this->setting->admin_page();

		add_settings_field(
			$this->get_id(),
			$this->get_name(),
			array( $this, 'render' ),
			$parent_id,
			$this->setting->get_id()
		);

		register_setting( $parent_id, $this->get_id() );
	}

	protected function refresh_id( ?int $id = null ): void {
		return;
	}

	protected function refresh_value(): void {
		if ( empty( $this->value ) ) {
			$this->value = get_option( $this->get_id() );
		}
	}

	protected function get_data_type(): string {
		return $this->DATA_TYPE;
	}

	protected function render_form_wrapper_open(): void {}

	protected function render_form_wrapper_close(): void {}
}
