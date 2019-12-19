# Meta Box
Create a meta box in post edit screen.

```php
use Sujin\Wordpress\WP_Express\Meta_Box;

Meta_Box::get_instance( 'Meta Box' )
    ->method( $value )
    ->method( $value )
    ->method( $value );
```

## Methods
### append( Abstract_Filed_Post_Meta $post_meta )
Append form field in this meta box

```php
use Sujin\Wordpress\WP_Express\Meta_Box;
use Sujin\Wordpress\WP_Express\Fields\Post_Meta\Input;

Meta_Box::get_instance( 'Meta Box' )
    ->append( Input::get_instance( 'Input Name' ) );
```

### append_to( Post_Type $post_meta )
Append this meta box in post type

```php
use Sujin\Wordpress\WP_Express\Meta_Box;
use Sujin\Wordpress\WP_Express\Post_Type;

Meta_Box::get_instance( 'Meta Box' )
    ->append_to( Post_Type::get_instance( 'Custom Post Type' ) );
```

## Methods (Options)
### context( Meta_Box_Context $value )
Set meta box context: https://developer.wordpress.org/reference/functions/add_meta_box/

```php
use Sujin\Wordpress\WP_Express\Meta_Box;
use Sujin\Wordpress\WP_Express\Helpers\Enums\Meta_Box_Context;

Meta_Box::get_instance( 'Meta Box' )
    ->context( Meta_Box_Context::advanced() );
```

### priority( Meta_Box_Priority $value )
Set meta box priority: https://developer.wordpress.org/reference/functions/add_meta_box/

```php
use Sujin\Wordpress\WP_Express\Meta_Box;
use Sujin\Wordpress\WP_Express\Helpers\Enums\Meta_Box_Priority;

Meta_Box::get_instance( 'Meta Box' )
    ->priority( Meta_Box_Priority::low() );
```
