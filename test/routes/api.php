<?php

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
        "callback" => function($props){
            return "Good morning {$props['name']}";
        }
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