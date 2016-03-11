<?php
/**
 *
 * WE\Settings Class
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

namespace WE;

if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Settings {
	private $settings = [];
	public $sections = [];
	private $set = false;

	public function __construct() {
		if ( func_num_args() > 0 && is_array( func_get_arg(0) ) ) {
			$arguments = func_get_arg(0);
			$this->sections = $arguments[0];

			foreach( $arguments[1] as $fieldKey => $fieldArray ) {
				$this->settings[ $fieldKey ] = new Setting( $fieldArray[ 'name' ] );

				foreach( $fieldArray as $optionKey => $optionValue ) {
					$this->settings[ $fieldKey ]->{ $optionKey } = $optionValue;
				}
			}
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

	public function addField( $fieldName ) {
		$fieldKey = sanitize_title( $fieldName );
		if ( in_array( $fieldKey, array_keys( $this->settings ) ) ) {
			return;
		}

		// Apply Set Item if exists
		$this->applySet();

		if ( !in_array( $fieldKey, array_keys( $this->settings ) ) ) {
			$this->settings[ $fieldKey ] = new Setting( $fieldName );

			if ( !$this->sections ) {
				$this->addSection();
			}

			end( $this->sections );
			$sectionKey = key( $this->sections );

			$this->sections[ $sectionKey ][ 'fields' ][] = $fieldKey;
		}

		return $fieldKey;
	}

	public function __get( $name ) {
		switch( $name ) {
			case 'set' :
				return ( !empty( $this->set ) ) ? $this->set : new \WE\Settings();
			break;

			case 'settings' :
				return $this->sections;
			break;
		}
	}

	public function __set( $name, $value ) {
		if ( $name === 'section' ) {
			$this->addSection( $value );
			return;
		}

		if ( !$this->sections ) return;

		// Set Item
		if ( $name === 'set' ) {
			if ( empty( $this->set ) ) {
				$this->set = new \WE\Settings();
				$this->set->addField( $value );

				$this->setOption( 'type', 'set' );
			} else {
				$this->set->addField( $value );
			}

			return;
		}

		$this->setOption( $name, $value );
	}

	public function getValue() {
		$settings = [];
		foreach( $this->settings as $settingKey => $setting ) {
			$settings[ $settingKey ] = $setting->getValue();
		}
		return $settings;
	}

	// Apply Set to Fields
	public function applySet() {
		if ( empty( $this->set ) || empty( $this->set->getValue() ) ) {
			$this->set = false;
			return;
		}

		$setValue = $this->set->getValue();

		end( $this->sections );
		$section_key_01 = key( $this->sections );

		end( $this->sections[ $section_key_01 ][ 'fields' ] );
		$section_key_02 = key( $this->sections[ $section_key_01 ][ 'fields' ] );

		$section = &$this->sections[ $section_key_01 ][ 'fields' ][ $section_key_02 ];
		$sectionKey = $section;
		$section = [ $section ];

		foreach( $this->set->settings as $setKey => $setValues ) {
			$newKey = sanitize_title( $sectionKey . '-' . $setKey );
			$this->settings[ $newKey ] = $setValues;
			$section[] = $newKey;
		}

		$this->set = false;
	}

	// Set Field's Option
	private function setOption( $name, $value ) {
		end( $this->settings );
		$field_key = key( $this->settings );

		$this->settings[ $field_key ]->{$name} = $value;
	}
}