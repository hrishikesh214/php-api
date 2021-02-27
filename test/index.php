<?php
//require 'vendor/autoload.php';
require '../autoload.php';

$api = new phpapi\Client();

$api->mount("post", "name", function(){
            return "You posted name : {$_POST['name']}";
        });
 $api->mount("get", "name/:name", function($params){
            return "You get name : {$params['name']}";
    });

//$api->trace();

print_r($api->run(isset($_GET['URL']) ? $_GET['URL'] : ""));

