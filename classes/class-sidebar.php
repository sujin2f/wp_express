<?php
/**
 * Gutenburg Sidebar Class
 *
 * @project WP-Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Sidebar extends Abs_Base {
	private $_icon      = null;
	private $_name      = '';
	private $_metaboxes = array();

	public function __construct( $name ) {
		parent::__construct( $name );
	}

	public function add( Meta_Box $metabox ): Sidebar {
		$this->_metaboxes[] = $metabox;
		return $this;
	}
}
