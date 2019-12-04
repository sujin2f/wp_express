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
	/**
	 * Get image URL(s)
	 *
	 * @return null|string|array
	 */
	public function get_image( ?int $id = null, string $size = 'full' ) {
		// Refresh value
		$this->refresh_id( $id );
		$this->refresh_value();

		if ( ! $this->value ) {
			return;
		}

		// Single
		if ( $this->option->single ) {
			$media = wp_get_attachment_image_src( $this->value, $size );
			return $media[0];
		}

		$return = array();

		foreach ( $this->value as $attachment_id ) {
			$media    = wp_get_attachment_image_src( $attachment_id, $size );
			$return[] = $media[0];
		}

		return $return;
	}

	/**
	 * Admin form
	 */
	protected function render_form(): void {
		// Refresh value
		$this->refresh_value();
		$upload_link = get_upload_iframe_src();
		$is_single   = $this->option->single;
		$value       = $is_single ? array( $this->value ) : $this->value;
		?>
		<section
			class="<?php echo esc_attr( self::PREFIX ); ?> field attachment"
			data-parent="<?php echo esc_attr( $this->get_id() ); ?>"
		>
			<section class="attachment__items">
				<?php
				foreach ( $value as $key => $attachment_id ) :
					$img_src = wp_get_attachment_image_src( $attachment_id )[0];

					if ( empty( $img_src ) ) {
						continue;
					}

					?>
					<section class="attachment__items__item" data-index="<?php echo esc_attr( $key ); ?>">
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
							class="btn-remove"
							data-parent="<?php echo esc_attr( $this->get_id() ); ?>"
							data-index="<?php echo esc_attr( $key ); ?>"
						>
							<span class="dashicons dashicons-no"></span>
						</button>
					</section>
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
