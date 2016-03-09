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

	private $save, $values, $setting_sections;
	private $transient = false;

	public function __construct() {
		parent::__construct( func_get_arg(0) );

		add_action( 'after_setup_theme', array( $this, 'initialize' ), 1 );
		add_action( 'after_setup_theme', array( $this, 'saveSettings' ), 2 );

		$this->setting_sections = new SettingObject();
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
			case 'value' :
			case 'values' :
				return $this->values;
			break;

			case 'setting' :
			case 'settings' :
			case 'option' :
			case 'options' :
				return $this->setting_sections;
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

				$this->setting_sections->addField( $value );
				return;
			break;
		}
	}

	function initialize() {
		$this->setting_sections->applySet();

		if ( $this->version !== '0.0.0' ) {
			$this->transient = get_transient( 'WP_Admin_Page_Settings-' . $this->key );

			if ( !$this->transient ) {
				$this->transient = [ $this->setting_sections->sections, $this->setting_sections->fields ];
				set_transient( 'WP_Admin_Page_Settings-' . $this->key, $this->transient, HOUR_IN_SECONDS );
			}

			$this->setting_sections = new SettingObject( $this->transient );
		} else {
			$this->transient = false;
			delete_transient( 'WP_Admin_Page_Settings-' . $this->key );
		}

		$this->initializeOptions();
	}

	// Load Setting from WP Option / Fill with the Default Vaule if not Exists
	private function initializeOptions() {
		$this->values = get_option( '_' . $this->key . '_', false );

		// If setting is Empty, Set Default
		if ( !$this->values ) {
			$this->setOptionsDefault();
		}
	}

	// Set Default Values to Options
	private function setOptionsDefault() {
		// Extract $settings_query's default value
		foreach( $this->setting_sections->fields as $field_key => $field ) {
			$this->values[ $field_key ] = ( !empty( $field['default'] ) ) ? $field['default'] : false;
		}
	}

	// Save
	function saveSettings() {
		if( !$_POST || !wp_verify_nonce( $_POST['_wpnonce'], $this->key . '-options' ) ) return false;
		unset( $_POST['option_page'], $_POST['action'], $_POST['_wpnonce'], $_POST['_wp_http_referer'], $_POST['submit'] );

		if ( $this->save ) {
			call_user_func( $this->save );

		} else {
			$this->values = apply_filters( 'WE_AdminPage_update_options_' . $this->key , $_POST );
			update_option( '_' . $this->key . '_', $this->values );

			$this->showMessage( 'Option Saved!' );
			do_action( 'WP_Admin_Page_' . $this->key . '_after_update_option' , $this->values );
		}
	}

	public function printTemplate( $contents = '' ) {
		$this->setSettingsSection();

		ob_start();
		if ( $this->version === '0.0.0' ) {
			printf( '<div class="description">The setting will be stored in <code>_%s_</code> option value. This message will be disappeared when you set <code>version</code> value. ( ig. 1.0.0 )</div>', $this->key );
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
		$is_file = false;

		foreach ( $this->setting_sections->sections as $section_key => $setting ) {
			add_settings_section( $section_key, $setting['name'], array( $this, 'settings_section_' . $section_key ), $this->key );

			# Set Fields
			foreach ( $setting['fields'] as $field_key ) {
				// is Set Item
				if ( is_array( $field_key ) ) {
					$set_key = array_shift( $field_key );
					$name = $this->setting_sections->fields[ $set_key ][ 'name' ];

					$field = [];
					foreach( $field_key as $key ) {
						$set_field = $this->setting_sections->fields[ $key ];
						$set_field[ 'key' ] = $key;
						$set_field[ 'value' ] = ( $this->values[ $key ] ) ? $this->values[ $key ] : '';

						if ( $set_field['type'] === 'file' ) $is_file = true;
						$field[] = $set_field;
					}

					$field_key = $set_key;
				} else {
					$field = $this->setting_sections->fields[ $field_key ];
					$name = ( $field[ 'type' ] === 'html' ) ? '' : $field[ 'name' ];

					$field[ 'key' ] = $field_key;
					$field[ 'value' ] = ( $this->values[ $field_key ] ) ? $this->values[ $field_key ] : '';

					if ( $field['type'] === 'file' ) $is_file = true;
				}

				add_settings_field( $field_key, $name, array( $this, 'settings_field_' . $field_key ), $this->key, $section_key, $field );
				register_setting( $section_key, $field );
			}
		}

		if ( $is_file ) {
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

class SettingObject {
	public $fields = [];
	public $sections = [];
	private $type = 'text';
	private $allowed_type = [ 'file', 'text', 'number', 'checkbox', 'select', 'html', 'textarea', 'set' ];
	private $set = false;

	public function __construct() {
		if ( func_num_args() > 0 && is_array( func_get_arg(0) ) ) {
			$option = func_get_arg(0);
			$this->sections = $option[0];
			$this->fields = $option[1];
		}
	}

	private function addSection( $name = false ) {
		if ( !$name ) {
			$this->sections[] =  array( 'name' => '', 'fields' => [] );
		} else {
			$section_key = sanitize_title( $name );

			if ( empty( $this->sections[ $section_key ] ) )
				$this->sections[ $section_key ] =  array( 'name' => $name, 'fields' => [] );
		}

		end( $this->sections );
		return key( $this->sections );
	}

	public function addField( $name ) {
		// Apply Set Item if exists
		$this->applySet();

		$field_key = sanitize_title( $name );

		if ( empty( $this->fields[ $field_key ] ) ) {
			$this->fields[ $field_key ] = array( 'name' => $name, 'type' => 'text' );

			if ( !$this->sections ) {
				$this->addSection();
			}

			end( $this->sections );
			$section_key = key( $this->sections );

			$this->sections[ $section_key ][ 'fields' ][] = $field_key;
		}

		return $field_key;
	}

	public function __get( $name ) {
		if ( $name === 'set' && !empty( $this->set ) ) {
			return $this->set;
		} else {
			return new SettingObject();
		}
	}

	public function __set( $name, $value ) {
		if ( $name === 'section' ) {
			$this->addSection( $value );
			return;
		}

		if ( !$this->fields ) return;

		// Set Item
		if ( $name === 'set' ) {
			if ( empty( $this->set ) ) {
				$this->set = new SettingObject();
				$this->set->addField( $value );

				$this->setOption( 'type', 'set' );
			} else {
				$this->set->addField( $value );
			}

			return;
		}

		$this->setOption( $name, $value );
	}

	// Apply Set to Fields
	public function applySet() {
		if ( empty( $this->set ) || empty( $this->set->fields ) ) {
			$this->set = false;
			return;
		}

		end( $this->sections );
		$section_key_01 = key( $this->sections );

		end( $this->sections[ $section_key_01 ][ 'fields' ] );
		$section_key_02 = key( $this->sections[ $section_key_01 ][ 'fields' ] );

		$section = &$this->sections[ $section_key_01 ][ 'fields' ][ $section_key_02 ];
		$section = [ $section ];

		end( $this->fields );
		$field_key = key( $this->fields );
		$fields = $this->set->fields;

		$this->set = false;

		foreach( $fields as $key => $values ) {
			$new_field_key = sanitize_title( $field_key . '-' . $key );

			if ( empty( $this->fields[ $new_field_key ] ) ) {
				$this->fields[ $new_field_key ] = array( 'name' => $values[ 'name' ], 'type' => 'text' );
				$section[] = $new_field_key;
			}

			unset( $values[ 'name' ] );

			foreach( $values as $val_key => $value ) {
				$this->setOption( $val_key, $value );
			}
		}
	}

	// Set Field's Option
	private function setOption( $name, $value ) {
		end( $this->fields );
		$field_key = key( $this->fields );

		switch( $name ) {
			case 'name' :
				$this->fields[ $field_key ][ 'name' ] = $value;
			break;

			case 'default' :
				$this->fields[ $field_key ][ 'default' ] = $value;
			break;

			case 'description' :
				$this->fields[ $field_key ][ 'description' ] = $value;
			break;

			case 'type' :
				if ( !in_array( strtolower( $value ), $this->allowed_type ) ) return;
				$this->fields[ $field_key ][ 'type' ] = $value;
			break;

			case 'html' :
				$this->fields[ $field_key ][ 'html' ] = $value;
			break;

			case 'class' :
				$this->fields[ $field_key ][ 'class' ] = $value;
			break;
		}
	}
}