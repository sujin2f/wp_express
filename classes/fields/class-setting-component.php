<?php
/**
 * Interface for Fields
 *
 * @package WP Express
 * @since   the beginning
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields;

use Sujin\Wordpress\WP_Express\Setting;
use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;

abstract class Setting_Component extends Filed_Component {
	use Trait_Multiton;

	/**
	 * @var Setting
	 */
	private $setting;

	public function append_to( Setting $setting ) {
		$this->setting        = $setting;
		$this->option->legend = $setting->get_name();
	}

	public function update( ?int $post_id = null, $value = null ): void {
	}

	protected function init(): void {
		add_action( 'admin_init', array( $this, 'add_settings_field' ) );
		add_filter( 'pre_update_option_' . $this->get_id(), array( $this, 'pre_update_option' ) );
	}

	public function pre_update_option( $value ) {
		if ( $this->is_single() && is_array( $value ) ) {
			$value = $value[0];
		}

		return $value;
	}

	public function get( ?int $_ = null ) {
		return get_option( $this->get_id() );
	}

	public function add_settings_field(): void {
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
			array( $this, 'render_setting_form' ),
			$parent_id,
			$this->setting->get_id()
		);

		register_setting( $parent_id, $this->get_id() );
	}

	public function render_setting_form( array $_ ) {
		$this->render_form();
	}

	protected function refresh_id( ?int $id = null ): void {
		return;
	}

	protected function refresh_value(): void {
		if ( empty( $this->value ) ) {
			$default_value = $this->is_single() ? null : array();
			$this->value = get_option( $this->get_id(), $default_value );
		}
	}

	protected function get_data_type(): string {
		return $this->DATA_TYPE;
	}

	protected function render_form_wrapper_open(): void {}

	protected function render_form_wrapper_close(): void {}
}
