<?php
/**
 * Interface for Fields
 *
 * @project WP-Express
 * @since   1.0.0
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Fields\Term_Meta;

use Sujin\Wordpress\WP_Express\Fields\Abs_Term_Meta_Element;
use Sujin\Wordpress\WP_Express\Fields\Elements\Trait_Attachment;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 404 Not Found' );
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

class Attachment extends Abs_Term_Meta_Element {
	use Trait_Attachment;

	protected function __construct( string $name, array $attrs = array() ) {
		parent::__construct( $name, $attrs );
		$this->add_script( WP_EXPRESS_ASSET_URL . '/' . self::$manifest['app.js'], true );
	}

	public function register_meta() {
		$args = array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		);
		register_meta( 'term', $this->get_id(), $args );
	}
}
