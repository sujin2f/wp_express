<?php
/**
 * Admin Allowed Position Enum
 *
 * @project WP Express
 * @author  Sujin 수진 Choi http://www.sujinc.com/
 */

namespace Sujin\Wordpress\WP_Express\Enum;

class Options_Admin_Position extends Enum {
	public const OPTION     = 'option';
	public const SETTINGS   = 'settings';
	public const TOOLS      = 'tools';
	public const USERS      = 'users';
	public const PLUGINS    = 'plugins';
	public const COMMENTS   = 'comments';
	public const PAGES      = 'pages';
	public const POSTS      = 'posts';
	public const MEDIA      = 'media';
	public const DASHBOARD  = 'dashboard';
	public const APPEARANCE = 'appearance';
}
