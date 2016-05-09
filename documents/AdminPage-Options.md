# AdminPage : Options Page
Options Page makes an admin page which the user set option values.
```php
$AdminPage = new WE\AdminPage\Options( 'Page Name' );
```

## Version
The version is important for the perfomance. When version value isn't declared or declared as '0.0.0', the small paragraph will be appeared on the admin page. Moreover, Options page calculates your setting whenever users visit the site. When you change the version, the setting will be updated and stored (It will be updated in every one hour).

## Save Callback
You can change the saving action by assigning a save callback property.
```php
$AdminPage->save = 'saveAdminPage';

function saveAdminPage() {
  /// Write the Code.
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
    /// Write the Code.
  }
}
```

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
You can get the setting data by calling $AdminPage->value.
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
