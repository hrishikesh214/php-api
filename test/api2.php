<?php


$routes = [
    [
        'match' => 'msg',
        'type' => 'get',
        'callback' => function(){
            return "trial v2";
        }
    ]
];

$config = [
    'base' => 'api/v2'
];