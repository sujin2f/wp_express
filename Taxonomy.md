## Taxonomy
To add an empty page, create a reference. Default is adding to post.
```php
$tax = new WE\Taxonomy( 'My Tag' );
```

### Settings
You can set initializing parameters.
See [https://codex.wordpress.org/Function_Reference/register_taxonomy] for more information.
```php
$tax->show_ui = false;
$tax->description = 'This is my new Taxonomy';
$tax->post = 'My Post Type';
```

### Taxonomy Meta
```php
$tax->section = 'Post Meta Set 1';
$tax->setting = "Image";
$tax->setting->type = 'file';

$tax->section = 'Post Meta Set 2';
$tax->setting = "Image Size";
$tax->set = 'Width';
$tax->set->type = 'number';
$tax->set = 'Height';
$tax->set->type = 'number';
```
