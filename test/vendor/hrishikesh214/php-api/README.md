# [`PHP` API](https://github.com/hrishikesh214/php-api)
## ```Easily create RESTFull API in PHP```

# Installation
You can install it from [Github](https://github.com/hrishikesh214/php-api) or from composer too.
```apacheconf
composer require hrishikesh214/php-api
```
And then
```php
require 'vendor/hrishikesh214/php-api/autoload.php';
```
or if installed using from Github
```php
require 'php-api/autoload.php';
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

This `$client->run()` returns response string, so you can store it in variable or directly print it

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
            "api/name": {
                "base": "api/name",
                "type": "GET",
                "params": {
                    "fname": 3,
                    "lname": 4
                    }
            },
            "token": {
                "base": "token",
                "type": "GET",
                "params": [ ]
            },
            "api/token": {
                "base": "api/token",
                "type": "POST",
                "params": [ ]
            },
            "api/wish": {
                "base": "api/wish",
                "type": "DELETE",
                "params": {
                    "name": 3
                }
            }
        },
        "base": "",
        "request_blocks": [
        "api",
        "name",
        "hrishi",
        "vaze"
        ],
        "request_uri": "api/name/john",
        "request_type": "GET"
    },
    "result": "Hello john , how are you?"
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


### Made with ❤️By [Hrishikesh](https://github.com/hrishikesh214)