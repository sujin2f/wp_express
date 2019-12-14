# Settings Section
Create a settings section.

```php
use Sujin\Wordpress\WP_Express\Settings_Section;

Settings_Section::get_instance( 'Settings Section' )
    ->method( $value )
    ->method( $value )
    ->method( $value );
```

## Methods
### append( Abstract_Filed_Setting $field )
Append form field in this settings section

```php
use Sujin\Wordpress\WP_Express\Settings_Section;
use Sujin\Wordpress\WP_Express\Fields\Settings\Input;

Settings_Section::get_instance( 'Settings Section' )
    ->append( Input::get_instance( 'Input Name' ) );
```

### append_to( string|Admin $admin )
Append this settings section in admin

```php
use Sujin\Wordpress\WP_Express\Settings_Section;
use Sujin\Wordpress\WP_Express\Admin;

Settings_Section::get_instance( 'Settings Section' )
    ->append_to( Admin::get_instance( 'Admin' ) )
    // or
    ->append_to( 'general' );
```
