<?php
require 'vendor/autoload.php';
//require '../autoload.php';
//echo "<pre>";
$api = new phpapi\Client();
$helper = new phpapi\Helper($api);

$helper->use('routes/api.php');
$helper->use('api2.php');

$api->mount("post", "name", function(){
            return "You posted name : {$_POST['name']}";
        });
 $api->mount("get", "name/:name", function($params){
            return "You get name : {$params['name']}";
    });

 $api->mount("get", "/", function(){
     return "hi how are you?";
 });

$api->trace();

print_r($api->run(isset($_GET['URL']) ? $_GET['URL'] : ""));