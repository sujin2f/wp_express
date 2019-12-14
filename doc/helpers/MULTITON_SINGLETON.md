# Multiton and Singleton

```php
use Sujin\Wordpress\WP_Express\Helpers\Trait_Multiton;
use Sujin\Wordpress\WP_Express\Helpers\Trait_Singleton;

class Multi {
    use Trait_Multiton;
}

class Single {
    use Trait_Singleton;
}

$multi  = Multi::get_instance( 'Name' );
$single = Single::get_instance();
```
