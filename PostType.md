## PostType
To add an empty page, create a reference.
```php
$PostType = new WE\AdminPage( 'My Art' );
```

### Settings
You can set initializing parameters.
See [https://codex.wordpress.org/Function_Reference/register_post_type] for more information.
```php
$PostType->public = false;
$PostType->description = 'This is my new Post Type';
$PostType->supports = 'author';
$PostType->supports = 'excerpt';
$PostType->supports = 'trackbacks';
$PostType->supports = [ 'comments', 'revisions' ];
```

### Post Meta
```php
$PostType->section = 'Post Meta Set 1';
$PostType->setting = "Image";
$PostType->setting->type = 'file';

$PostType->section = 'Post Meta Set 2';
$PostType->setting = "Image Size";
$PostType->set = 'Width';
$PostType->set->type = 'number';
$PostType->set = 'Height';
$PostType->set->type = 'number';
```

### Taxonomy
```php
$PostType->taxonomy = 'My Tag';
```

Or you can add \WE\Taxonomy.
```php
$tax = new WE\Taxonomy( 'My Tag' );
$PostType->taxonomy = $tax;
```

