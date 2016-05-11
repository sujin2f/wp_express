# AdminPage : Options Page
```php
$AdminPage = new WE\AdminPage\Options( 'Page Name' );
```
Options Page is the page which provides input fields.

![Options Page Example](https://github.com/sujin2f/wp_express/blob/master/documents/images/AdminOption_001.png "Options Page Example")

## Version
The version is important for the perfomance. When version value isn't declared or declared as '0.0.0', an explanation will be appeared on the admin page. Moreover, Options page calculates your setting whenever users visit the site. When you change the version, the setting will be updated and stored.

## Settings
```php
$AdminPage->section = "Setting Section"; // Make Section (Optional)

$AdminPage->setting = "Image Size"; // Make New Input Field Named Image Size. It has to be unique within a page. ( Default : text )
$AdminPage->setting->type = "number"; // Set the type of Image Size as number
$AdminPage->setting->default = "200"; // Set Default Value
$AdminPage->setting->description = "Image Size MUST be less than 1000";
$AdminPage->setting->class = "large-text"; // The class attribute of input tag ( Default : regular-text )

$AdminPage->setting = "Thumnail Size"; // Make New Input Field
```
You can get the setting data by calling ```$AdminPage->value```.
```php
$setting = $AdminPage->value;
```

### Supported Type
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

## Saving Callback
The saving values are automatically stored into WP options (see the explanation on your Options Page). If you have something to do when the saving action is triggered, this saving callback can be used.
```php
$AdminPage->save = 'saveAdminPage';

function saveAdminPage() {
	// Do Something,
}
```
You can also call a method as well.
```php
class myPluginInit {
	function __construct() {
		include_once( 'wp_express/autoload.php' );
		$AdminPage = new WE\AdminPage( 'My Admin Page' );
		$AdminPage->save = array( $this, 'saveTemplate' );
	}
  
	function saveTemplate() {
		// Do Something,
	}
}
```
