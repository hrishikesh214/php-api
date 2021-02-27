<?php
//require 'vendor/autoload.php';
require '../autoload.php';

$api = new phpapi\Client();

$api->mount("get", 'api/name/:fname/:lname', function($props){
    return wish($props);
});

$api->mount("GET", 'token', function(){
    return md5(rand(100,1000));
});

$api->mount("POST", 'token', function(){
    return md5(rand(100,1000));
});

$api->mount("POST", 'api/token', function(){
    return json_encode(["token" => md5(rand(100,1000))], JSON_PRETTY_PRINT);
});
$gg = function($props){
    extract($props);
    return "gg $name";
};
$api->mount('get', 'api/wish/:name', $gg);

$api->trace();

print_r($api->run(isset($_GET['URL']) ? $_GET['URL'] : ""));


function wish($props){
    return "Hello ". $props['fname'] ." ". $props['lname'] . ", how are you?";
}

