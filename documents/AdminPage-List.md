# AdminPage : List Page
List Page makes an admin page which shows a list.
```php
$AdminPage = new WE\AdminPage\ListPage( 'Page Name' );
```
## Add Columns
```php
$AdminPage->column = 'Number';
$AdminPage->column = 'Link';
```
## Add Sortable Columns
```php
$AdminPage->sortable = 'Name';
```

## Set Data, Number of Items, and Items per Page
```php
$AdminPage->data = 'patchData'; // Set Callback
$AdminPage->count = 'patchCount'; // Set Callback
$AdminPage->per_page = 100;

// The keys have to be matched with coulumn names.
function patchData() {
	return array(
		array(
			'Number' => 1,
			'Link' => '<a href="#">Link</a>',
			'Name' => 'Your Link'
		)
	);
}

function patchCount() {
	return 100;
}
```
