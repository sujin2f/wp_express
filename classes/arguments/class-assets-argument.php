<?php
/**
 * Assets Argement
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Types;

class Assets_Argument extends Abstract_Arguments {
    /*
     * @var ?string[]
     */
    private $depends;

    /*
     * @var ?bool
     */
    private $is_admin;

    /*
     * @var ?bool
     */
    private $is_footer;

    /*
     * @var ?array
     */
    private $translation;

    /*
     * @var ?string
     */
    private $translation_key;

    /*
     * @var Assets_Type
     */
    private $type;

    /*
     * @var string
     */
    private $url;

    /*
     * @var ?string
     */
    private $version;
}
