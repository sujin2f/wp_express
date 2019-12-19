# Admin Menu
Create a blank admin menu page.

```php
use Sujin\Wordpress\WP_Express\Admin;
Admin::get_instance( 'Admin Root' )
    ->method( $value )
    ->method( $value )
    ->method( $value );
```

## Methods
### append( Settings_Section $setting )
Append settings section in this admin menu

```php
use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Settings_Section;

Admin::get_instance( 'Admin' )
    ->append( Settings_Section::get_instance( 'Setting' ) );
```

## Methods (Options)
### position( string|int|Admin_Position|Admin|Post_Type $value )
Set the position of the menu. Root menu only. Default vaslue `settings`.

The value can be `string`, `integer`, Enum `Admin_Position`, other `Admin`, or `Post_Type`.

```php
use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Post_Type;
use Sujin\Wordpress\WP_Express\Helpers\Enums\Admin_Position;

Admin::get_instance( 'Admin' )
    // Can be one of
    ->position( 'general' )
    ->position( 'position' )
    ->position( 100 )
    ->position( Admin_Position::tools() )
    ->position( Admin::get_instance( 'Admin Root' ) )
    ->position( Post_Type::get_instance( 'Custom Post Type' ) );
```

### icon( string $value )
WP Dashicon string. Show in front of the admin title: https://developer.wordpress.org/resource/dashicons/#admin-home Default vaslue `dashicons-admin-generic`.

```php
use Sujin\Wordpress\WP_Express\Admin;

Admin::get_instance( 'Admin' )->icon( 'dashicons-admin-home' );
```

### capability( string $value )
Capability string for access control: https://wordpress.org/support/article/roles-and-capabilities/ Default vaslue `manage_options`.

```php
use Sujin\Wordpress\WP_Express\Admin;

Admin::get_instance( 'Admin' )->capability( 'publish_posts' );
```

### plugin( string $value )
Plugin name. It creates Setting link in the Plugins page.

```php
use Sujin\Wordpress\WP_Express\Admin;

Admin::get_instance( 'Admin' )->plugin( 'My Plugin' );
```
