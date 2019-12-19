# JSON Schema
JSON schema validator

```php
use Sujin\Wordpress\WP_Express\Helpers\Schema;

class Temp_Schema extends Schema {
    protected function get_base_dir(): string {
        return '/The/Schema/Root/Dir';
    }
}

class Simple implements JsonSerializable {
    public $title  = 'Title';
    public $url    = 'http://test.com';
    public $number = '1';
    public $object = array(
        'child' => 'http://test.com',
    );

    public function jsonSerialize(): array {
        // This will read a file: /The/Schema/Root/Dir/simple.json
        return Temp_Schema::from_file( 'simple.json' )->filter( $this );
    }
}

$simple        = new Simple();
$simple->title = 'Title';
$simple->url   = 'https://url.com';

// Validate by schema and get value
$validated_value = json_decode( wp_json_encode( $simple ), true );
```
