<?php
/**
 * Assets Helper
 *
 * @package WP Express
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Types;

class Assets_Argument {
    /*
     * @var ?string[]
     */
    public $depends;

    /*
     * @var ?bool
     */
    public $is_admin;

    /*
     * @var ?bool
     */
    public $is_footer;

    /*
     * @var ?array
     */
    public $translation;

    /*
     * @var ?string
     */
    public $translation_key;

    /*
     * @var Assets_Type
     */
    public $type;

    /*
     * @var string
     */
    public $url;

    /*
     * @var ?string
     */
    public $version;
}
