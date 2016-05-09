# WordPress Express
Quick Wordpress Development Module

## Initialize
Include autoload.php, and you are ready to use this!
```php
include_once( 'wp_express/autoload.php' );
```

* [Admin Page](https://github.com/sujin2f/wp_express/blob/master/documents/AdminPage.md)
* [Post Type](https://github.com/sujin2f/wp_express/blob/master/documents/PostType.md)
* [Taxonomy](https://github.com/sujin2f/wp_express/blob/master/documents/Taxonomy.md)

Setting options and meta data is same for each componants.

### Settings
```php
$AdminPage->section = "Setting Section"; // Make Section (Optional)

$AdminPage->setting = "Image Size"; // Make New Input Field Named Image Size. It has to be unique within a page. ( Default : text )
$AdminPage->setting->type = "number"; // Set the type of Image Size as number
$AdminPage->setting->default = "200"; // Set Default Value
$AdminPage->setting->description = "Image Size MUST be less than 1000";
$AdminPage->setting->class = "large-text"; // The class attribute of input tag ( Default : regular-text )

$AdminPage->setting = "Thumnail Size"; // Make New Input Field
```

#### Supported Type
* file
* text
* number
* checkbox
* html
* textarea

You can add HTML into your form.
```php
$AdminPage->setting = "HTML"; // It won't appear if you set this as html type
$AdminPage->setting->type = "html";
$AdminPage->setting->html = "<p>This is HTML</p>";
```

You can make set input fields. It appears on one row.
```php
$AdminPage->setting = "Image Size";

$AdminPage->set = 'Width'; // New Field. This ID will be image-size-width.
$AdminPage->set->type = 'number';
$AdminPage->set->default = 370;
$AdminPage->set->class = 'small-text';

$AdminPage->set = 'Height';
$AdminPage->set->type = 'number';
$AdminPage->set->default = 250;
$AdminPage->set->class = 'small-text';
```
