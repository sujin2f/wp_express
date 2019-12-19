# WordPress Express

[![Build Status](https://travis-ci.org/sujin2f/wp_express.svg)](https://travis-ci.org/sujin2f/wp_express)
[![Coverage Status](https://coveralls.io/repos/github/sujin2f/wp_express/badge.svg?branch=master)](https://coveralls.io/github/sujin2f/wp_express?branch=master)

<p align="center" style="background-color:black;">
  <img src="logo.png">
</p>

Quick Wordpress Development Module which helps you to make new admin pages, custom post types, and taxonomies.

## Initialize
Include autoload.php, and you are ready.

```php
include_once( $your_path_to . '/wp_express/autoload.php' );
```

## Usage
```php
use Sujin\Wordpress\WP_Express\Admin;
use Sujin\Wordpress\WP_Express\Settings_Section;
use Sujin\Wordpress\WP_Express\Fields\Settings\Input;

// Create a new admin page in the root of admin menu
$admin_page_root = Admin::get_instance( 'Admin Root');
// Change position by position id
$admin_page_root->position( 200 );

// Create another admin page under $test_admin_page_1
$admin_page_child = Admin::get_instance( 'Admin Child' )
	->position( $root );
// Change position under Settings
$admin_page_child->position( 'settings' );
// Change position under other menu
$admin_page_child->position( 'Plugin Name' );
// Add a link in the Plugins list
$admin_page_child->plugin( 'Akismet' );
// Set menu icon (use WP Dashicons)
$admin_page_child->icon( 'dashicons-buddicons-activity' );

// Create a new setttings section (default location is settings page)
$settings_section = Settings_Section::get_instance( 'Settings Section' );
// Append the settings section into the admin page
$admin_page_child->append( $settings_section );

// Create a new settting field `headline`
$headline = Input::get_instance( 'Headline' );
// Append the input into the settings section
$settings_section->append( $headline );
// Change the input type to date
$headline->type( 'date' );
// Change the input to the multiple value
$headline->single( false );

// Settngs could be the chaining assignment
$headline
	->show_in_rest( true )
	->single( true )
	->append_to( $settings_section );
```

This is the example of creating a new admin page and set the setting block and its text input. You can create a new post type, taxonomy, and its fields.

### Admin Items
- [Admin Menu](doc/admin-items/ADMIN_MENU.md)
- [Settings Section](doc/admin-items/SETTINGS_SECTION.md)
- [Post Type](doc/admin-items/POST_TYPE.md)
- [Meta Box](doc/admin-items/META_BOX.md)
- [Taxonomy](doc/admin-items/TAXONOMY.md)

### Form Fields
- [Attachment](doc/form-fields/ATTACHMENT.md)
- [Checkbox](doc/form-fields/CHECKBOX.md)
- [Editor](doc/form-fields/EDITOR.md)
- [Input](doc/form-fields/INPUT.md)
- [Radio](doc/form-fields/RADIO.md)
- [Select](doc/form-fields/SELECT.md)
- [Textarea](doc/form-fields/TEXTAREA.md)

helpers
### Helpers
- [Argument](doc/helpers/ARGUMENT.md)
- [Assets Loader](doc/helpers/ASSETS_LOADER.md)
- [Autoloader](doc/helpers/AUTOLOADER.md)
- [Enum](doc/helpers/ENUM.md)
- [Google Font Loader](doc/helpers/GOOGLE_FONT_LOADER.md)
- [JSON Schema](doc/helpers/JSON_SCHEMA.md)
- [Multiton/Singleton](doc/helpers/MULTITON_SINGLETON.md)
- [Transient](doc/helpers/TRANSIENT.md)
