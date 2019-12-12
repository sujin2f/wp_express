<?php
/**
 * Enum Admin Position
 *
 * @author  Sujin 수진 Choi <http://www.sujinc.com/>
 * @package WP Express
 * @since   4.0.0
 */

namespace Sujin\Wordpress\WP_Express\Helpers\Enums;

use Sujin\Wordpress\WP_Express\Helpers\Abstract_Enum;

class Admin_Position extends Abstract_Enum {
	public const OPTION     = array( 'option', 'settings' );
	public const TOOLS      = 'tools';
	public const USERS      = 'users';
	public const PLUGINS    = 'plugins';
	public const COMMENTS   = 'comments';
	public const PAGES      = 'pages';
	public const POSTS      = 'posts';
	public const MEDIA      = 'media';
	public const DASHBOARD  = 'dashboard';
	public const APPEARANCE = 'appearance';

	public static function get_parent_slug( string $keyword ): ?string {
		if ( ! static::in_array( $keyword ) ) {
			return null;
		}

		switch ( Admin_Position::$keyword()->case() ) {
			case Admin_Position::OPTION:
				return 'options-general.php';
			case Admin_Position::TOOLS:
				return 'tools.php';
			case Admin_Position::USERS:
				return 'users.php';
			case Admin_Position::PLUGINS:
				return 'plugins.php';
			case Admin_Position::COMMENTS:
				return 'edit-comments.php';
			case Admin_Position::PAGES:
				return 'edit.php?post_type=page';
			case Admin_Position::POSTS:
				return 'edit.php';
			case Admin_Position::MEDIA:
				return 'upload.php';
			case Admin_Position::DASHBOARD:
				return 'index.php';
			case Admin_Position::APPEARANCE:
				return 'themes.php';
		}

		return null;
	}
}
