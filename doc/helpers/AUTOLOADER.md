# Autoloader
General autoloader class.

```php
include_once( $path_to . 'wp-express/classes/class-autoloader.php' );

$class_loader = new Sujin\Wordpress\WP_Express\Autoloader(
	'Your\\Namespace',
	dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes',
);
$class_loader->register();
```

This autoloader supports Wordpress class structure and `index.php`.

Class `Class_Name` should be camel case, and the file name would be `class-class-name`.

`index.php` refers the directory as a class.

```
- classes
    - my-module
        - index.php           \Your\Namespace\My_Module
        - class-helper.php    \Your\Namespace\My_Module\Helper
```
