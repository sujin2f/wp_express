<?php
use Sujin\Wordpress\WP_Express\Helpers\Assets;

class Assets_Test extends Test_Case {
	/*
	 * Test initialize with no manifest
	 */
	public function test_none_manifest(): void {
		$assets = Assets::get_instance( 'Test' );
		$assets->add_script( $this->get_stylesheet_directory_uri() . '/assets/dist/script.js' )
			->is_admin(true)
			->is_footer(true)
			->translation( array( 'foo' => 'bar' ) )
			->translation_key( 'baz' )
		;
		$assets = $this->get_private_property( $assets, 'assets' );
		$assets = array_pop( $assets );

		$this->assertEquals(
			'http://example.org/wp-content/themes/twentynineteen/assets/dist/script.js',
			$assets->url,
			'😡 Asset URL is not matched',
		);

		$this->assertEquals(
			true,
			$assets->is_admin,
			'😡 Asset admin setting is not matche',
		);

		$this->assertEquals(
			true,
			$assets->is_footer,
			'😡 Asset footer setting is not matched',
		);

		$this->assertEquals(
			array( 'foo' => 'bar' ),
			$assets->translation,
			'😡 Asset translation setting is not matched',
		);

		$this->assertEquals(
			'baz',
			$assets->translation_key,
			'😡 Asset translation_key setting is not matched',
		);
	}
}
