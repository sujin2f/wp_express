<?php
/**
 *
 * WE\Setting Class
 *
 * @author	Sujin 수진 Choi
 * @package	wp-express
 * @version	4.0.0
 * @website	http://sujinc.com
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice
 *
 */

namespace WE\Extensions;

if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Setting extends Abs {
	private $allowed_type = [ 'file', 'text', 'number', 'checkbox', 'select', 'html', 'textarea', 'set' ];
	protected $defaultName = 'New Setting';

	private $default;
	public $description, $html, $class;
	public $type = 'text';

	public function __construct() {
		$name = ( !func_num_args() ) ? false : func_get_arg(0);
		parent::__construct( $name );
	}

	public function __set( $name, $value ) {
		parent::__set( $name, $value );

		switch( $name ) {
			case 'type' :
				if ( !in_array( strtolower( $value ), $this->allowed_type ) ) return;
				$this->type = $value;
				break;

			case 'default' :
				if ( empty( $this->values ) ) $this->values = $value;
				$this->default = $value;
				break;
		}
	}

	public function printSettingsField() {
		$class = ( !empty( $this->class ) ) ? $this->class : 'regular-text';

		switch ( $this->type ) {
			case 'file' :
				$upload_link = get_upload_iframe_src();
				$media_arr = wp_get_attachment_image_src( $this->value );

				$img = $this->value ? '<img src="' . $media_arr[0] . '" width="150" />' : '';

				?>
				<div id="<?php echo $this->key ?>-custom-img-container"><?php echo $img ?></div>
				<a id="<?php echo $this->key ?>-upload-custom-img" class="<?php if ( $img ) { echo 'hidden'; } ?>" href="<?php echo $upload_link ?>"><?php _e( 'Set custom image' ) ?></a>
				<a id="<?php echo $this->key ?>-delete-custom-img" class="<?php if ( !$img ) { echo 'hidden'; } ?>" href="#"><?php _e( 'Remove this image' ) ?></a>

				<input id="<?php echo $this->key ?>-custom-img-id" name="<?php echo $this->key ?>" type="hidden" value="<?php echo esc_attr( $this->value ); ?>" />
				<?php

				add_action( 'admin_footer', array( $this, 'printMediaUploadScript' ) );
				add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
			break;

			case 'text' :
				?>
				<input type="text" name="<?php echo $this->key ?>" id="<?php echo $this->key ?>" value="<?php echo $this->value ?>" class="<?php echo $class ?>" />
				<?php
			break;

			case 'number' :
				?>
				<input type="number" name="<?php echo $this->key ?>" id="<?php echo $this->key ?>" value="<?php echo $this->value ?>" class="<?php echo $class ?>" />
				<?php
			break;

			case 'checkbox' :
				$class = ( !empty( $this->class ) ) ? $this->class : '';
				?>
				<label for="<?php echo $this->key ?>">
					<input type="checkbox" name="<?php echo $this->key ?>" id="<?php echo $this->key ?>" class="<?php echo $class ?>" <?php if ( $this->value ) echo 'checked="checked"'; ?> />
					<?php echo $this->name ?>
				</label>
				<?php
			break;

			case 'select' :
				$class = ( !empty( $this->class ) ) ? $this->class : '';
				?>
				<select name="<?php echo $this->key ?>" id="<?php echo $this->key ?>" class="<?php echo $class ?>">
					<?php
					if ( !empty( $this->values['options'] ) ) {
						foreach( $this->values['options'] as $options ) {
							if ( is_array( $options ) && ( !array_key_exists( 'value', $options ) || !array_key_exists( 'name', $options ) ) ) {
								$options["value"] = $options["name"] = array_shift( $options );

							} else if ( !is_array( $options ) ) {
								$options = array( 'value' => $options, 'name' => $options );

							}
							?>
							<option value="<?php echo $options["value"] ?>" <?php if ( $options["value"] === $this->value ) echo 'selected="selected"'; ?>><?php echo $options["name"] ?></option>
						<?php
						}
					}
					?>
				</select>
				<?php
			break;

			case 'html' :
				echo $this->html;
			break;

			case 'textarea' :
				$class = ( !empty( $this->class ) ) ? $this->class : 'large-text';
				?>
				<textarea  name="<?php echo $this->key ?>" id="<?php echo $this->key ?>" class="<?php echo $class ?>" rows="8"><?php echo $this->value ?></textarea>
				<?php
			break;

		}

		if ( !empty( $this->description ) ) {
			echo '<p class="description">' . $this->description . '</p>';
		}
	}

	public function printMediaUploadScript() {
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function($) {
				var frame;
				$( '#<?php echo $this->key ?>-upload-custom-img' ).click( function( e ) {
					e.preventDefault();

					if( !frame ) {
						frame = wp.media({
							title: 'Select or Upload Media Of Your Chosen Persuasion',
							button: { text: 'Use this media' },
							multiple: false
						});

						frame.on( 'select', function() {
							var attachment = frame.state().get('selection').first().toJSON();
							$( '#<?php echo $this->key ?>-custom-img-container' ).append( '<img src="' + attachment.url + '" width="150" />' );
							$( '#<?php echo $this->key ?>-custom-img-id' ).val( attachment.id );

							$( '#<?php echo $this->key ?>-upload-custom-img' ).addClass( 'hidden' );
							$( '#<?php echo $this->key ?>-delete-custom-img' ).removeClass( 'hidden' );
						});
					}

					frame.open();
				});

				$( '#<?php echo $this->key ?>-delete-custom-img' ).on( 'click', function( e ){
					e.preventDefault();

					$( '#<?php echo $this->key ?>-custom-img-container' ).html( '' );
					$( '#<?php echo $this->key ?>-custom-img-id' ).val( '' );

					$( '#<?php echo $this->key ?>-upload-custom-img' ).removeClass( 'hidden' );
					$( '#<?php echo $this->key ?>-delete-custom-img' ).addClass( 'hidden' );
				});
			});
		</script>
		<?php
	}
}