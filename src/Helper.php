<?php

namespace phpapi;
require_once __dir__ . '/../autoload.php';

class Helper{

    protected \phpapi\Client $client;

    public function __construct( \phpapi\Client $client ){
        $this->client = $client;
    }

    public function use(String $path, String $basename = NULL){
        if( file_exists($path) && !is_dir($path)){
            include $path;
            if(isset($config['base'])){
                $base = $config['base'];
            }
            $basename = isset($base) ? $base : $basename;
            $basename = (substr($basename, -1) != '/') ? $basename .'/' : $basename;
            foreach($routes as $route){
                $this->client->mount($route['type'], $basename . $route['match'], $route['callback']);
            }
        }
        else{
            trigger_error("Given path not exists or path is a directory: PATH = ".$path, E_USER_ERROR);
        }
        unset($routes, $config);
    }

}