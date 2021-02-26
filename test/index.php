<?php

require __dir__.'/../autoload.php';

$api = new phpapi\Client();

$api->mount("get", 'api/name/:fname/:lname', function($props){
    return wish($props);
});

$api->mount("GET", 'token', function(){
    return md5(rand(100,1000));
});

$api->mount("POST", 'api/token', function(){
    return json_encode(["token" => md5(rand(100,1000))], JSON_PRETTY_PRINT);
});

$api->mount('delete', 'api/wish/:name', function (){
    return $_POST;
});

$api->trace(false);

print_r($api->run(isset($_GET['URL']) ? $_GET['URL'] : ""));


function wish($props){
    return "Hello ". $props['fname'] ." ". $props['lname'] . ", how are you?";
}