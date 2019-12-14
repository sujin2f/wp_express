# Meta Box
Create a custom post type.

```php
use Sujin\Wordpress\WP_Express\Post_Type;

Post_Type::get_instance( 'Custom Post Type' )
    ->method( $value )
    ->method( $value )
    ->method( $value );
```

## Methods
### append( Meta_Box $metabox )
Append meta box in this post type

```php
use Sujin\Wordpress\WP_Express\Post_Type;
use Sujin\Wordpress\WP_Express\Meta_Box;

Post_Type::get_instance( 'Custom Post Type' )
    ->append( Meta_Box::get_instance( 'Meta Box' ) );
```

## Methods (Options)
Methods can be anything in the register_post_type() argument. https://developer.wordpress.org/reference/functions/register_post_type/

```php
use Sujin\Wordpress\WP_Express\Post_Type;

Post_Type::get_instance( 'Custom Post Type' )
    ->show_in_rest( true );
```
