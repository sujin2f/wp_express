<?php
/**
 *
 * Abstraction Class
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

class ListPage extends \WP_List_Table {
	public $site_id, $data, $count;
	public $per_page = 100;

	private $columns = [];
	private $sortable_columns = [];

	public function __construct( $args = array() ) {
		parent::__construct();
	}

	public function __set( $name, $value ) {
		switch( $name ) {
			case 'column' :
				if ( is_array( $value ) ) {
					foreach( $value as $val ) {
						$key = sanitize_title( $val );
						$this->columns[ $key ] = __( $val );
					}
				}
				break;

			case 'sortable_columns' :
				if ( is_array( $value ) ) {
					foreach( $value as $val ) {
						$key = sanitize_title( $val );
						$this->sortable_columns[ $key ] = array( $key, true );
					}
				}
				break;
		}
	}

	public function prepare_items() {
		$paged = $this->get_pagenum();
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$data = call_user_func( $this->data );

 		$this->items = $data;

		$this->set_pagination_args( array(
			'total_items' => call_user_func( $this->count ),
			'per_page' => ( $this->per_page ) ? $this->per_page : 100
		) );
	}

	public function no_items() {
		_e( 'No Items found.' );
	}

	protected function get_views() {
/*
		$view["view_all"] = sprintf( '<a href="%s">View all Codes</a>', add_query_arg( array( "group_id" => "all" ), remove_query_arg( array( "orderby", "order") ) ) );
		return $view;
*/
	}


	public function single_row( $row ) {
		$r = "<tr>";
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {
			$r .= "<td>";

			if ( array_key_exists( $column_name, $this->columns ) ) {
				$r.= $row[ $this->columns[ $column_name ] ];
			}

			$r .= "</td>";
		}

		$r.= "</tr>";

		return $r;
	}

	public function get_columns() {
		return $this->columns;
	}

	protected function get_sortable_columns() {
		return $this->sortable_columns;
	}

	public function display_rows() {
		foreach ( $this->items as $group ) {
			echo "\n\t" . $this->single_row( $group );
		}
	}

}
