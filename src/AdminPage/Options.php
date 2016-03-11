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
	use \WE\HtmlTrait;

	private $save, $settings;
	private $transient = false;
	protected $value;

	public function __construct() {
		$name = ( !func_num_args() ) ? false : func_get_arg(0);
		parent::__construct( $name );

		add_action( 'after_setup_theme', array( $this, 'initialize' ), 1 );
		add_action( 'after_setup_theme', array( $this, 'saveSettings' ), 2 );

		$this->settings = new \WE\Settings();
	}

	// ! Callbacks : for setting section and field
	public function __call( $name, $arguments ) {
		# Settings Section
		if ( strpos( $name, 'settings_section_' ) === 0 ) {
			return true;
		}

		# Settings Section
		if ( strpos( $name, 'settings_field_' ) === 0 ) {
			return $this->callPrintSettingsField( $arguments[0] );
		}
	}

	public function __get( $name ) {
		switch( $name ) {
			case 'setting' :
			case 'settings' :
			case 'option' :
			case 'options' :
				return $this->settings;
			break;
		}

		return false;
	}

	public function __set( $name, $value ) {
		if ( parent::__set( $name, $value ) ) return;

		switch( $name ) {
			case 'save' :
				$this->save = $value;
				return;
			break;

			case 'setting' :
			case 'settings' :
			case 'option' :
			case 'options' :
				$this->transient = get_transient( 'WP_Admin_Page_Settings-' . $this->key );
				if ( $this->version !== '0.0.0' && $this->transient ) return;

				$this->settings->addField( $value );
				return;
			break;
		}
	}

	function initialize() {
		$this->settings->applySet();

		if ( $this->version !== '0.0.0' ) {
			$this->transient = get_transient( 'WP_Admin_Page_Settings-' . $this->key );

			if ( !$this->transient || $this->transient[0] !== $this->version ) {
				$this->transient = [ $this->version, $this->settings->sections, $this->settings->getValue() ];
				set_transient( 'WP_Admin_Page_Settings-' . $this->key, $this->transient, HOUR_IN_SECONDS );
			}

			$this->settings = new \WE\Settings( [ $this->transient[1], $this->transient[2] ] );
		} else {
			$this->transient = false;
			delete_transient( 'WP_Admin_Page_Settings-' . $this->key );
		}

		$this->initializeOptions();
	}

	// Load Setting from WP Option / Fill with the Default Vaule if not Exists
	private function initializeOptions() {
		$this->value = get_option( '_' . $this->key . '_', false );

		// If setting is Empty, Set Default
		if ( !$this->value ) {
			$this->setOptionsDefault();
		}
	}

	// Set Default Values to Options
	private function setOptionsDefault() {
		// Extract $settings_query's default value

		foreach( $this->settings->getValue() as $field_key => $field ) {
			$this->value[ $field_key ] = ( !empty( $field['default'] ) ) ? $field['default'] : false;
		}
	}

	// Save
	function saveSettings() {
		if( !$_POST || !wp_verify_nonce( $_POST['_wpnonce'], $this->key . '-options' ) ) return false;
		unset( $_POST['option_page'], $_POST['action'], $_POST['_wpnonce'], $_POST['_wp_http_referer'], $_POST['submit'] );

		if ( $this->save ) {
			call_user_func( $this->save );

		} else {
			$this->value = apply_filters( 'WE_AdminPage_update_options_' . $this->key , $_POST );
			update_option( '_' . $this->key . '_', $this->value );

			$this->showMessage( 'Option Saved!' );
			do_action( 'WP_Admin_Page_' . $this->key . '_after_update_option' , $this->value );
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
	private function setSettingsSection() {
		$isFile = false;
		$settings = $this->settings->getValue();

		foreach ( $this->settings->sections as $sectionKey => $section ) {
			add_settings_section( $sectionKey, $section['name'], array( $this, 'settings_section_' . $sectionKey ), $this->key );

			# Set Fields
			foreach ( $section['fields'] as $fieldKey ) {
				// is Set Item
				if ( is_array( $fieldKey ) ) {
					$setKey = array_shift( $fieldKey );
					$name = $settings[ $setKey ][ 'name' ];

					$field = [];

					foreach( $fieldKey as $key ) {
						$setField = $settings[ $key ];

						$setField[ 'key' ] = $key;
						$setField[ 'value' ] = ( $this->value[ $key ] ) ? $this->value[ $key ] : '';

						if ( $setField['type'] === 'file' ) $isFile = true;
						$field[] = $setField;
					}

					$fieldKey = $setKey;
				} else {
					$field = $settings[ $fieldKey ];
					$name = ( $field[ 'type' ] === 'html' ) ? '' : $field[ 'name' ];

					$field[ 'key' ] = $fieldKey;
					$field[ 'value' ] = ( $this->value[ $fieldKey ] ) ? $this->value[ $fieldKey ] : '';

					if ( $field['type'] === 'file' ) $isFile = true;
				}

				add_settings_field( $fieldKey, $name, array( $this, 'settings_field_' . $fieldKey ), $this->key, $sectionKey, $field );
				register_setting( $sectionKey, $field );
			}
		}

		if ( $isFile ) {
			add_action( 'admin_footer', function() { $this->printMediaUploadScript( 'form-' . $this->key ); } );
			add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
		}
	}

	# Setting API Fields
	private function callPrintSettingsField( $arg ) {
		if ( isset( $arg[ 'type' ] ) ) {
			$this->printSettingsField( $arg );

			if ( !empty( $arg[ 'description' ] ) ) {
				echo '<p class="description">' . $arg[ 'description' ] . '</p>';
			}
		} else {
			foreach ( $arg as $multiple_type ) {
				$this->callPrintSettingsField( $multiple_type );
			}
		}
	}
}

