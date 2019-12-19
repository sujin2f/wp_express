# Google Font Loader
Load Google Font asynchronously

```php
use Sujin\Wordpress\WP_Express\Google_Font_Loader;

Google_Font_Loader::get_instance()
    ->append( 'Roboto+Condensed:400,700:latin' )
    ->append( 'other font' );
```
