# [`PHP` API](https://github.com/hrishikesh214/php-api)
### ```Easily create RESTFull API in PHP```
###
```css
It supports only JSON but you can change output :-) 
```

# Installation
```apacheconf
composer require hrishikesh214/php-api
```

And then in your file
```php
require 'vendor/autoload.php';
```

# Configurations
Before getting into `PHPAPI` we have to make some configs

In your root directory, create `.htaccess` file and paste following code
```apacheconf
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?URL=$1 [L]
```

# Documentation
### `Creating a client`
```php
$client = new phpapi\Client();
```
You can also pass `base address`
```php
$client = new phpapi\Client("api/");
```
All child endpoints will be access after `api/`

## `Running Client`
```php
print_r($client->run(isset($_GET['URL']) ? $_GET['URL'] : ""));
```

This `$client->run()` returns response string, so you can store it in variable for further process or directly print it as response

### `Creating an Endpoint`
```php
$client->mount('GET', 'token', function(){
    //some calculations
    return $token;
});
```
Only some request methods are allowed : `'PUT', 'POST', 'DELETE', 'PATCH', 'GET', 'PURGE'`

`mount` returns boolean value about whether endpoint is mounted successfully or not

### `Passing URL Parameters`

`Client` will automatically pass url parameters to the response function

example is given below
```php
$client->mount('GET', 'wish/:name/:age', function($props){
    return "Hi $props['name'], you are $props['age] years old!";
});
 ```
URI Parameters are directly passed to the responder function in an associative array according to the key passed in parameter

All other `posted` parameters will automatically get stored in `$_POST`

`NOTE` If parameter is define while mounting but not passes then it is given a null value 

### `POST Endpoint`
```php
$respond = function(){
    return $_POST; //It will have all posted data!
};
$client->mount("POST", 'checkpost', $respond);
```

### Trace Whole API
You can trace whole API Client with detailed configs of all endpoints mounted with a function
```php
$client->trace(true|false);
```
If Above value is true final api result will also included api configs

_**This Also includes Requested configs**_

Example output:- 
```json
{
  "track": {
    "routes": {
      "POST": {
        "api/wish": {
          "base": "api/wish",
          "type": "POST",
          "params": [ ]
        },
        "name": {
          "base": "name",
          "type": "POST",
          "params": [ ]
        }
      },
      "GET": {
        "api/msg": {
          "base": "api/msg",
          "type": "GET",
          "params": [ ]
        },
        "api/wish": {
          "base": "api/wish",
          "type": "GET",
          "params": {
            "name": 3
          }
        },
        "name": {
          "base": "name",
          "type": "GET",
          "params": {
            "name": 3
          }
        },
        "/": {
          "base": "/",
          "type": "GET",
          "params": [ ]
        }
      }
    },
    "base": "",
    "request_blocks": [
      "api",
      "msg"
    ],
    "request_uri": "api/msg",
    "request_type": "GET"
  },
  "result": "trial"
}
```

In above example result will contain result coming from API

## Setting Request Errors
There is a default error handler but you change it!
### 404

```php
$client->set404([
    'error_type' => 404,
    'error_msg' => "Not Found"
]);
```

### 405

```php
$client->set405([
    'error_type' => 405,
    'error_msg' => "Method Not Allowed"
]);
```
## External Routes
### `Importing Routes from external file`
You can also define routes in external file all you need is to use `Helper` Class.

For example (folder structure):- 
```
|   myRoutes
|       - api.php
|   vendor (composer files)
|   index.php
```
```php
//index.php
$client = new phpapi\Client();
$helper = new phpapi\Helper($client);
```
```php
// myRoutes/api.php

// You can define functions and also pass to callback
$myFunc = function($props){
            return "Good morning {$props['name']}";
        };

$routes = [
    [
        "match" => 'msg',
        "type" => "get",
        "callback" => function(){
            return "trial";
        }
    ],
    [
        "match" => 'wish/:name',
        "type" => "get",
        "callback" => $myFunc
    ],
    [
        "match" => 'wish',
        "type" => "post",
        "callback" => function(){
            return "Good morning {$_POST['name']}";
        }
    ]
];

$config = [
    'base' => 'api'
];
```

In above code `$config['base']'` will be act as base to all routes present in this file.

`$routes` will contain all routes.

*Please maintain format else code will not work!*


### Made with ❤️By [Hrishikesh](https://github.com/hrishikesh214)