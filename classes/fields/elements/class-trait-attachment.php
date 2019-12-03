<?php
/**
 * Common helper for Attachment
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

trait Trait_Attachment {
	protected $defaults_attributes = array(
		'class' => 'regular-text',
	);

	/**
	 * Get image URL(s)
	 *
	 * @return null|string|array
	 */
	public function get_image( ?int $maybe_id = null, string $size = 'full' ) {
		// Refresh value
		$this->refresh_value( $maybe_id );

		if ( ! $this->attributes['value'] ) {
			return;
		}

		// Single
		if ( $this->options['single'] ) {
			$media = wp_get_attachment_image_src( $this->attributes['value'], $size );
			return $media[0];
		}

		$return = array();

		foreach ( $this->attributes['value'] as $attachment_id ) {
			$media    = wp_get_attachment_image_src( $attachment_id, $size );
			$return[] = $media[0];
		}

		return $return;
	}

	protected function is_available(): bool {
		return true;
	}

	/**
	 * Admin form
	 */
	protected function render_form(): void {
		// Refresh value
		$this->refresh_value();
		$upload_link = get_upload_iframe_src();
		$is_single   = $this->options['single'];
		$value       = $this->options['single'] ? array( $this->attributes['value'] ) : $this->attributes['value'];

		$media_arr   = wp_get_attachment_image_src( $this->attributes['value'] );
		$img          = $this->attributes['value'] ? esc_attr( $media_arr[0] ) : '';
		$class_upload = $img ? 'hidden' : '';
		$class_remove = empty( $img ) ? 'hidden' : '';

		?>
		<section
			class="<?php echo esc_attr( self::PREFIX ); ?> field attachment"
			data-id="<?php echo esc_attr( $this->get_id() ); ?>"
		>
			<section class="attachment__single">
				<?php
				foreach ( $value as $key => $attachment_id ) :
					$img_src = wp_get_attachment_image_src( $attachment_id )[0];
					?>
					<input
						name="<?php echo esc_attr( $this->get_id() ); ?>[<?php echo esc_attr( $key ); ?>]"
						type="hidden"
						value="<?php echo esc_attr( $attachment_id ); ?>"
					/>

					<div
						class="img-container"
						style="background-image: url('<?php echo $img_src; ?>');"
					></div>

					<button
						class="<?php echo esc_attr( $class_remove ); ?> btn-remove"
						data-key="<?php echo esc_attr( $key ); ?>"
					>
						<span class="dashicons dashicons-no"></span>
					</button>
				<?php endforeach; ?>
			</section>

			<a
				class="button btn-upload"
				href="<?php echo esc_url_raw( $upload_link ); ?>"
				<?php echo $is_single ? esc_attr( 'data-single' ) : ''; ?>
			>
				<?php if ( $is_single ) : ?>
					Select Image
				<?php else : ?>
					Select Images
				<?php endif; ?>
			</a>
		</section>
		<?php

		wp_enqueue_media();
	}
}
