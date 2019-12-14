# Taxonomy
Create a custom taxonomy.

```php
use Sujin\Wordpress\WP_Express\Taxonomy;

Taxonomy::get_instance( 'Kind' )
    ->method( $value )
    ->method( $value )
    ->method( $value );
```

## Methods
### append( Abstract_Filed_Term_Meta $field )
Append form field in this taxonomy

```php
use Sujin\Wordpress\WP_Express\Taxonomy;
use Sujin\Wordpress\WP_Express\Fields\Term_Meta\Input;

Taxonomy::get_instance( 'Kind' )
    ->append( Input::get_instance( 'Input Name' ) );
```

### append_to( string|Post_Type $post_type )
Append this taxonomy section in the post type

```php
use Sujin\Wordpress\WP_Express\Taxonomy;
use Sujin\Wordpress\WP_Express\Post_Type;

Taxonomy::get_instance( 'Kind' )
    ->append_to( Post_Type::get_instance( 'Post Type' ) )
    // or
    ->append_to( 'post' );
```
