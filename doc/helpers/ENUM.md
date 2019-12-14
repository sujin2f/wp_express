# Enum
Enumerable object

```php
use Sujin\Wordpress\WP_Express\Helpers\Abstract_Enum;

class Enum extends Abstract_Enum {
    public const STRING = 'value';
    public const NUMBER  = array( 'int', 'integer', 'float', 'decimal' );
}

Enum::value(); // Refers STRING
Enum::int(); // Refers NUMBER
Enum::integer(); // Refers NUMBER
```

## Methods
### case()
Converts instance into Enum const. It's useful in `switch()`.

```php
$enum_instance = Enum::int();

switch( $enum_instance->case() ) {
    case Enum::STRING: // false
        break;
    case Enum::NUMBER: // true
        break;
}
```
