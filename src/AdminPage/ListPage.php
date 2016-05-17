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

class ListPage extends \WE\AdminPage {
	private $columns = [];
	private $sortable_columns = [];
	public $data, $count, $per_page;

	public function __construct() {
		$name = ( !func_num_args() ) ? false : func_get_arg(0);
		parent::__construct( $name );
	}

	public function __get( $name ) {
		if ( $value = parent::__get( $name ) ) return $value;
		return $this->get( $name );
	}

	public function __set( $name, $value ) {
		if ( parent::__set( $name, $value ) ) return;

		switch( $name ) {
			case 'column' :
				$this->columns[] = $value;
				break;

			case 'sortable' :
			case 'sortable_column' :
				$this->columns[] = $value;
				$this->sortable_columns[] = $value;
				break;
		}
	}

	public function printTemplate( $contents = '' ) {
		if( !class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}

		$table = new \WE\Extensions\ListPage();
		$table->column = $this->columns;
		$table->sortable_columns = $this->sortable_columns;
		$table->data = $this->data;
		$table->count = $this->count;
		$table->per_page = $this->per_page;

		$table->prepare_items();

		ob_start();

		if ( $this->template ) {
			$template = array_shift( $this->template );

			if ( $template ) call_user_func( $template );
		}

		$table->views();
		$table->display();

		$contents = ob_get_clean();

		parent::printTemplate( $contents );
	}
}