<?php
/**
 * Common helper for Attachment
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   the beginning
 */

namespace Sujin\Wordpress\WP_Express\Fields\Elements;

trait Trait_Attachment {
	protected $data_type = 'integer';

	/**
	 * Admin form
	 */
	protected function render_form_field(): void {
		$upload_link = get_upload_iframe_src();
		$is_single   = $this->is_single();
		$value       = $is_single ? array( $this->value ) : $this->value;
		?>
		<section
			class="<?php echo esc_attr( self::PREFIX ); ?> field attachment"
			data-id="<?php echo esc_attr( $this->get_id() ); ?>"
		>
			<section class="attachment__items">
				<?php
				foreach ( $value as $key => $attachment_id ) :
					$img_src = wp_get_attachment_image_src( $attachment_id )[0];

					if ( empty( $img_src ) ) {
						continue;
					}

					?>
					<section
						class="attachment__items__item"
						data-id="<?php echo esc_attr( $key ); ?>"
					>
						<input
							name="<?php echo esc_attr( $this->get_id() ); ?>[<?php echo esc_attr( $key ); ?>]"
							type="hidden"
							value="<?php echo esc_attr( $attachment_id ); ?>"
						/>

						<div class="img-container" style="background-image: url('<?php echo esc_url( $img_src ); ?>');"></div>

						<button class="btn-remove">
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
