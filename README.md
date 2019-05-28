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
use Sujin\Wordpress\WP_Express\Setting;
use Sujin\Wordpress\WP_Express\Fields\Settings\Input;

$root = Admin::get_instance('Test Admin Root'); // Create a new admin page

Admin::get_instance( 'Test Admin' ) // Create another admin page
	->position( $root ) // Set a position to the previous menu
	->position( 'settings' ) // Set a position into default menu
	->position( 'Plugin Name' ) // Set a position into any menu
	->position( 200 ) // Set a position by number
	->plugin( 'Akismet' ) // Create a link to the admin page into the plugin page
	->icon( 'dashicons-buddicons-activity' ) // Set menu icon
	
	/*
	 * Setting classes can be placed into the Admin
	 */
	->add(
		Setting::get_instance( 'Settings Block' ) // Add a new settting block
			->add( Input::get_instance( 'Headline' ) ) // Add a new input into the setting block
	);
```

This is the example of creating a new admin page and set the setting block and its text input. You can create a new post type, taxonomy, and its fields like this. Other examples are in (tests/class-browser-test.php)
