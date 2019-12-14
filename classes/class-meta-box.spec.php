<?php
/**
 * Meta_Box Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

use Sujin\Wordpress\WP_Express\Meta_Box;
use Sujin\Wordpress\WP_Express\Post_Type;

class Meta_Box_Test extends Test_Case {
	public function test_register_meta_box() {
		global $wp_meta_boxes;

		$metabox = Meta_Box::get_instance( 'Metabox 1' )
			->append_to( Post_Type::get_instance( 'Post' ) );
		$metabox->register_meta_box();

		$this->assertTrue(
			array_key_exists(
				$metabox->get_id(),
				$wp_meta_boxes['post']['advanced']['default'],
			),
			'😡 Meta box registration failed.',
		);
	}

	public function test_set_post_type() {
		$metabox   = Meta_Box::get_instance( 'Metabox 2' );
		$post_type = Post_Type::get_instance( 'Post type' );
		$metabox->append_to( $post_type );

		$post_types = $this->get_private_property( $metabox, 'post_types' );

		$this->assertEquals(
			$post_type->get_id(),
			$post_types[0]->get_id(),
			'😡 Meta box - Post type assignment failed.',
		);
	}

	public function test_add_and_show_field() {
		ob_start();
		$metabox = Meta_Box::get_instance( 'Metabox 3' )->show_meta_box();
		$html    = ob_get_clean();

		$expected1 = '_nonce';
		$expected2 = '_wp_http_referer';

		$this->assertContains(
			$expected1,
			$html,
			'😡 Meta box does not have nonce field.',
		);
		$this->assertContains(
			$expected2,
			$html,
			'😡 Meta box does not have _wp_http_referer.',
		);
	}
}
