# PHP BillPlz

This is a simple library for working with Malaysia's BillPlz online payment. Be sure that you read the BillPlz API Documentation and understand what it offers before using this library.

https://billplz.com/api

## Installation

### Composer

```composer require kidino/billplz```

### Github

Just download any of the release or clone this repository. You may need to manage how you load the library with namespacing yourself.

## How to use

### Create New Collection

Be sure that you save the result. Result you get the is normal Curl result.

```php
use Kidino\Billplz\Billplz;

$bplz = new Billplz(array('api_key' => 'your api key'));
$bplz->set_data('title','Home Tutoring');
$bplz->set_data('logo','/xampp/htdocs/billplz/logo.png');
$result = $bplz->create_collection();
list($rheader, $rbody) = explode("\n\n", $result);
$bplz_result = json_decode($rbody);
```

### Create a New Bill

Please note that you can also use `set_data()` with an array instead of two parameters.

```php
use Kidino\Billplz\Billplz;

$bplz = new Billplz(array('api_key' => 'your api key'));
$bplz->set_data(array(
	'collection_id' => 'your collection id',
	'email' => 'customer@email.com',
	'mobile' => '60123456789',
	'name' => "Jone Doe",
	'due_at' => "2016-1-1",
	'amount' => 2000, // RM20
	'callback_url' => "http://yourwebsite.com/return_url"
));

$result = $bplz->create_bill();
list($rheader, $rbody) = explode("\n\n", $result);
$bplz_result = json_decode($rbody);
```

### Get Bill Details

```php
use Kidino\Billplz\Billplz;

$bplz = new Billplz(array('api_key' => 'your api key'));
$result = $bplz->get_bill( 'your bill id' );
list($rheader, $rbody) = explode("\n\n", $result);
$bplz_result = json_decode($rbody);
```

### Delete Bill

```php
use Kidino\Billplz\Billplz;

$bplz = new Billplz(array('api_key' => 'your api key'));
$result = $bplz->delete_bill( 'your bill id' );
list($rheader, $rbody) = explode("\n\n", $result);
$bplz_result = json_decode($rbody);
```
