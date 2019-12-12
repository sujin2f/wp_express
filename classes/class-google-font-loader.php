<?php
/**
 * Google Font Loader: Load Google Font asynchronously
 *
 * ```
 * Google_Font_Loader::get_instance()
 * 	->append( 'Roboto+Condensed:400,700:latin' );
 * ```
 * 
 * The value can be both an array and a string
 * 
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express;

use Sujin\Wordpress\WP_Express\Helpers\Trait_Singleton;

class Google_Font_Loader {
	use Trait_Singleton;

	private const WEBFONT_JS = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';

	private $fonts = array();

	protected function __construct() {
		add_action( 'wp_print_scripts', array( $this, 'import_google_font_asynchronously' ) );
	}

	/*
	 * Push font(s) in the queue
	 * @param string|string[] $fonts
	 */
	public function append( $fonts ): void {
		if ( is_array( $fonts ) ) {
			$this->fonts = array_merge( $this->fonts, $fonts );
		}

		if ( is_string( $fonts ) ) {
			array_push( $this->fonts, $fonts );
		}
	}

	/*
	 * Print the inline script
	 * https://github.com/typekit/webfontloader
	 */
	public function import_google_font_asynchronously(): void {
		if ( ! is_array( $this->fonts ) || 0 === count( $this->fonts ) ) {
			return;
		}

		$this->fonts = wp_json_encode( $this->fonts );

		?>
		<script>
		WebFontConfig = {
			google: {
				families: <?php echo $this->fonts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- broken quote ?>
			}
		};

		(function(d) {
			var wf = d.createElement('script'), s = d.scripts[0];
			wf.src = '<?php echo esc_url( self::WEBFONT_JS ) ?>';
			wf.async = true;
			s.parentNode.insertBefore(wf, s);
		})(document);
		</script>
		<?php
	}
}
