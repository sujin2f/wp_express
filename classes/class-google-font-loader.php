<?php
/**
 * GoogleFontImporter
 *
 * @project WP Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express;

/*
 * Google Font Loader: Load Google Font asynchronously
 *
 * Initialize like new Google_Font_Loader( 'Roboto+Condensed:400,700:latin' );
 * The value can be both an array and a string
 */
class Google_Font_Loader extends Component {
	private $fonts = array();

	protected function __construct( $fonts ) {
		parent::__construct();

		if ( is_array( $fonts ) ) {
			$this->fonts = array_merge( $this->fonts, $fonts );
		}

		if ( is_string( $fonts ) ) {
			array_push( $this->fonts, $fonts );
		}

		add_action( 'wp_print_scripts', array( $this, 'import_google_font_asynchronously' ) );
	}

	/*
	 * Print the inline script
	 */
	public function import_google_font_asynchronously() {
		if ( ! is_array( $this->fonts ) || 0 === count( $this->fonts ) ) {
			return;
		}

		$this->fonts = wp_json_encode( $this->fonts );

		// This code is from webfontloader (https://github.com/typekit/webfontloader)
		?>
		<script>
		WebFontConfig = {
			google: {
				families: <?php echo $this->fonts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- quote will be broken ?>
			}
		};

		(function(d) {
			var wf = d.createElement('script'), s = d.scripts[0];
			wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
			wf.async = true;
			s.parentNode.insertBefore(wf, s);
		})(document);
		</script>
		<?php
	}
}
