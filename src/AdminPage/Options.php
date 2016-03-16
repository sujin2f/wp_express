<?php
/**
 *
 * WP_Admin_Page Class
 *
 * @author	Sujin 수진 Choi
 * @package	wp-hacks
 * @version	3.0.0
 * @website	http://sujinc.com
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice
 *
 */

namespace WE\AdminPage;

if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Options extends \WE\AdminPage {
	use \WE\Extensions\HtmlHelper;
	use \WE\Extensions\StoredInfoSet;

	public function __construct() {
		$name = ( !func_num_args() ) ? false : func_get_arg(0);
		parent::__construct( $name );

		$this->initOptionSetting( 'WP_Admin_Page_Settings-' . $this->key );

		add_action( 'init', array( $this, 'saveSettings' ), 5 );
		add_action( 'init', array( $this, 'readFromDb' ), 50 );
	}

	public function __get( $name ) {
		return $this->getOptionSetting( $name );
	}

	public function __set( $name, $value ) {
		if ( parent::__set( $name, $value ) ) return;
		if ( $this->setOptionSetting( $name, $value ) ) return;
	}

	private function checkIsSaving() {
		if( !$_POST ) return false;
		if( !isset( $_POST[ '_wpnonce' ] ) ) return false;
		if( !wp_verify_nonce( $_POST[ '_wpnonce' ], $this->key . '-options' ) ) return false;

		delete_transient( $this->transientKey );
		return true;
	}

	public function readFromDb() {
 		$this->values = get_option( '_' . $this->key . '_', false );

 		foreach( $this->options as $key => $option ) {
			if ( array_key_exists( $key, $this->values ) )
				$option->value = $this->values[ $key ];
 		}
	}

	public function printTemplate( $contents = '' ) {
		$this->setSettingsSection();

		ob_start();
		if ( $this->version === '0.0.0' ) {
			printf( '<div class="description">The setting will be stored in <code>_%s_</code> option value. You can call it using <code>getValue()</code> method as well. This message will be disappeared when you set <code>version</code> value. ( ig. 1.0.0 )</div>', $this->key );
		}

		?>
		<form id="form-<?php echo $this->key ?>" method="POST" enctype="multipart/form-data">
			<?php settings_fields( $this->key ); ?>
			<?php do_settings_sections( $this->key ); ?>
			<?php submit_button( 'Submit', 'primary' ); ?>
		</form>
		<?php

		$contents = ob_get_clean();

		parent::printTemplate( $contents );
	}

	// ! Register Sections and Fields
	public function setSettingsSection() {
		foreach ( $this->sections as $sectionKey => $section ) {
			add_settings_section( $sectionKey, $section['name'], false, $this->key );

			# Set Fields
			foreach ( $section['fields'] as $fieldKey ) {
				// is Set Item
				if ( is_array( $fieldKey ) ) {
					$setKey = array_shift( $fieldKey );
					$name = $this->options[ $setKey ]->name;

					$setting = [];

					foreach( $fieldKey as $key ) {
						$setting[] = $this->options[ $key ];
					}

					$fieldKey = $setKey;

				} else {
					$setting = $this->options[ $fieldKey ];
					$name = ( $setting->type === 'html' ) ? '' : $setting->name;
				}

				add_settings_field( $fieldKey, $name, array( $this, 'callPrintSettingsField' ), $this->key, $sectionKey, [ $setting ] );
				register_setting( $sectionKey, [ $setting ] );
			}
		}
	}

	# Setting API Fields
	public function callPrintSettingsField( $setting ) {
		$setting = $setting[0];

		if ( !is_array( $setting ) ) {
			$setting->printSettingsField();
		} else {
			foreach ( $setting as $setting_ ) {
				$this->callPrintSettingsField( [ $setting_ ] );
			}
		}
	}

	// Save
	public function saveSettings() {
		if( !$this->isSaving ) return;

		if ( $this->save ) {
			call_user_func( $this->save );

		} else {
			update_option( '_' . $this->key . '_', $_POST );
			$this->showMessage( 'Option Saved!' );
		}
	}
}