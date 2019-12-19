# Argument(s)
Chain method style argument helper.

```php
use Sujin\Wordpress\WP_Express\Arguments\Abstract_Argument;
use Sujin\Wordpress\WP_Express\Helpers\Trait_With_Argument;

class Argument_Test extends Abstract_Argument {
    /*
     * @var string|int
     */
    protected $arg = 'settings'; // Default value
    protected function set_arg( $value ): bool { // set_{argument_name}() is for format validation
        if ( is_string( $value ) ) {
            return true;
        }

        if ( is_int( $value ) ) {
            return true;
        }

        return false;
    }

    /*
     * @var string
     */
    protected $arg2;
    protected function set_arg2( string $value ): bool {} // non-mixed value validation
}

// The class which uses this helper
class With_Argument {
    use Trait_With_Argument;

    protected function __construct( string $name ) {
        // Should be initialized
        $this->argument = new Argument_Test();
    }

    private function some_method(): void {
        $this->argument->get( 'arg' );
        $this->argument->set( 'arg', 'string' );
    }
}

$sample = new With_Argument();
// Set arguments
$sample->arg( 'string' );
$sample->arg2( 'string' );

// This will make an error
$sample->arg2( 100 );
```

## Methods
### set( string $key, $value )
### get( string $key )
### to_array()
### has( string $key )
