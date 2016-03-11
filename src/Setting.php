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

namespace WE;

if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Setting extends Abs {
	private $allowed_type = [ 'file', 'text', 'number', 'checkbox', 'select', 'html', 'textarea', 'set' ];

	public function __construct() {
		$name = ( !func_num_args() ) ? false : func_get_arg(0);
		parent::__construct( $name );

		$this->value[ 'type' ] = 'text';
		$this->value[ 'name' ] = $this->name;
		$this->value[ 'key' ] = $this->key;
	}

	public function __set( $name, $value ) {
		switch( $name ) {
			case 'type' :
				if ( !in_array( strtolower( $value ), $this->allowed_type ) ) return;
			case 'name' :
				$this->name = $value;
			case 'key' :
				$value = sanitize_title( $value );
				$this->key = $value;
			case 'default' :
			case 'description' :
			case 'html' :
			case 'class' :
			case 'value' :
				$this->value[ $name ] = $value;
			break;
		}
	}
}