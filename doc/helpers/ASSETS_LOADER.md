# Assets Loader
Script and style helper.

```php
// Pass manifest and base url. This loads assets automatically.
$asset = Assets::get_instance(
    array( 'app.js' => 'app.9sdf83jdFs09.js' ),
    'http://your.site/assets/',
);
$asset->append( 'app.js' ); // will load http://your.site/assets/app.9sdf83jdFs09.js

// Load assets with full path
$asset = Assets::get_instance( 'Assets group' );
$asset->append( get_stylesheet_directory_uri() . '/styles/style.css' );
```

## Methods
### append( string $url )
Register and enqueue asset. Style and script are organized by its file extension.

## Methods (Options)
### is_admin( bool $value )
### is_footer( bool $value )
### version( string $value )
### translation_key( string $value )
### translation( array $value )
