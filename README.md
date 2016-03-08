# WordPress Express
Quick Wordpress Development Module

## Initialize
Include autoload.php, and you are ready to use this!
```php
include_once( 'wp_express/autoload.php' );
```

## AdminPage : Simple Page
To add an empty page, create a reference.
```php
$AdminPage = new WE\AdminPage( 'Page Name' );
```

### Position
You can simply set by assigning a name of the menu. The default is Settings menu.
```php
$AdminPage->position = 'Appearance';
```
You can also set number to put it as a new menu as well.
```php
$AdminPage->position = 25;
```
This means you can apply submenus into your main menu.
```php
$RootPage = new WE\AdminPage( 'Root Page' );
$RootPage->position = 100;
$SubPage = new WE\AdminPage( 'Sub Page' );
$SubPage->position = 'Root Page';
```

### Template Callback
You can add contents by assigning a template callback property.
```php
$AdminPage->template = 'echoAdminPage';

function echoAdminPage() {
  /// Write the Code.
}
```
You can also call a method as well.
```php
class myPluginInit {
  function __construct() {
    include_once( 'wp_express/autoload.php' );
    $AdminPage = new WE\AdminPage( 'My Admin Page' );
    $AdminPage->template = array( $this, 'adminTemplate' );
  }
  
  function adminTemplate() {
    /// Write the Code.
  }
}
```

### Key
You can change the page's key which appears in an address bar.
```php
$AdminPage->key = 'my-key';
```

### Script and Style
By assigning script and style, include them. It will affect only for the page you created.
```php
$AdminPage->script = 'http://where.is/file.js';

$AdminPage->style = 'http://where.is/file1.css';
$AdminPage->style = 'http://where.is/file2.css';
```

### Version
The version property affects to javascripts and css versions. It also affects to Admin Options Page. The default value is 0.0.0
```php
$AdminPage->version = '1.0.0';
```

### Plugin
If you assing plugin name, the Setting link will appear on the table of Plugins menu.
```php
$AdminPage->plugin = 'My Plugin';
```

### Capability
The default value is 'activate_plugins'.
```php
$AdminPage->capability = 'read';
```

## AdminPage : Options Page
Options Page makes an admin page which the user set option values.
```php
$AdminPage = new WE\AdminPage\Options( 'Page Name' );
```

### Version
The version is important for the perfomance. When version value isn't declared or declared as '0.0.0', the small paragraph will be appeared on the admin page. Moreover, Options page calculates your setting whenever users visit the site. When you change the version, the setting will be updated and stored (It will be updated in every one hour).

### Save Callback
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
