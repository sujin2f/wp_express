<?php
/**
 * Google_Font_Loader Unit Test
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

use Sujin\Wordpress\WP_Express\Google_Font_Loader;

class Google_Font_Loader_Test extends Test_Case {
	public function test_loader(): void {
		Google_Font_Loader::get_instance()
			->append( 'Roboto+Condensed:400,700:latin' )
			->append(
				array(
					'this font',
					'that font',
				)
			);

		ob_start();
		do_action( 'wp_print_scripts' );
		$actual = ob_get_clean();

		$this->assertContains(
			'families: ["Roboto+Condensed:400,700:latin","this font","that font"]',
			$actual,
			'😡 Fonts were not fully loaded.',
		);
	}
}
