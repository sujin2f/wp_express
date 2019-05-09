<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

trait Trait_Attachment {
	protected $_defaults_attributes = array(
		'class' => 'regular-text',
	);

	public function __construct( string $name, array $attrs = array() ) {
		parent::__construct( $name, $attrs );
		$this->add_script( WP_EXPRESS_ASSET_URL . '/scripts/media_upload.js', true, true );
	}

	protected function _is_available(): bool {
		return true;
	}

	protected function _render_form() {
		$upload_link = get_upload_iframe_src();
		$media_arr   = wp_get_attachment_image_src( $this->_attributes['value'] );

		$img          = $this->_attributes['value'] ? '<img src="' . esc_attr( $media_arr[0] ) . '" width="150" />' : '';
		$class_upload = $img ? 'hidden' : '';
		$class_remove = empty( $img ) ? 'hidden' : '';

		?>
		<div id="<?php echo esc_attr( $this->get_id() ); ?>-custom-img-container"><?php echo $img; ?></div>

		<a
			id="<?php echo esc_attr( $this->get_id() ); ?>-upload-custom-img"
			class="wp_express <?php echo esc_attr( $class_upload ); ?> btn-upload"
			data-id="<?php echo esc_attr( $this->get_id() ); ?>"
			href="<?php echo esc_url_raw( $upload_link ); ?>"
		>
			<?php echo __( 'No image selected', 'wp-express' ); ?>
			<button class="button"><?php echo __( 'Add image', 'wp-express' ); ?></button>
		</a>

		<button
			id="<?php echo esc_attr( $this->get_id() ); ?>-delete-custom-img"
			class="wp_express <?php echo esc_attr( $class_remove ); ?> button btn-remove"
			data-id="<?php echo esc_attr( $this->get_id() ); ?>"
		>
			<?php echo __( 'Remove image', 'wp-express' ); ?>
		</button>

		<input
			id="<?php echo esc_attr( $this->get_id() ); ?>-custom-img-id"
			name="<?php echo esc_attr( $this->get_id() ); ?>"
			type="hidden"
			value="<?php echo esc_attr( $this->_attributes['value'] ); ?>"
		/>
		<?php

		wp_enqueue_media();
	}
}
