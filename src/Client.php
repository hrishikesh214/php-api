<?php /** @noinspection ALL */

namespace phpapi;

class Client{

    protected String $_base = "";
    protected array $_headers = [];
    protected array $_result = [];
    protected array $_routes = [
        'PUT' => [],
        'POST' => [],
        'DELETE' => [],
        'PATCH' => [],
        'GET' => [],
        'PURGE' => []
    ];
    protected array $_raw_routes = [];
    protected array $_request_blocks = [];
    protected bool $isTrace = false;
    protected array | string $_404_responder;
    protected array | string $_405_responder;
    protected array $triggered = [];
    protected array $_supported_methods = [
        'PUT', 'POST', 'DELETE', 'PATCH', 'GET', 'PURGE'
    ];

	public function __construct($base=''){
	    $this->setBasePath($base);
	    $this->set404([
	        'error' => '404 NOT FOUND'
        ]);
	    $this->set405([
	        'error' => '405 '.$_SERVER['REQUEST_METHOD'].' REQUEST NOT ALLOWED'
        ]);
	    $this->_raw_routes = $this->_routes;
	}

	public function setBasePath($base){
	    if($base == NULL || $base == ''){
	        return;
        }
        if($base[0] == '/'){
            $base = substr($base, 1);
        }
	    if(substr($base, -1) != '/'){
	        $base .= '/';
        }
	    $this->_base = $base;
    }

	public function mount($type = "GET", $path, $responder): bool
    {
        $type = strtoupper(trim($type));
        if( !in_array($type, $this->_supported_methods)){
            return false;
        }
        $segments = ($path == '/') ? "" : explode('/', $path);
        $path = [];
        $params = [];
        $indexer = 3;
        if(is_array($segments)){
            foreach($segments as $segment){
                if($segment == '/'){ $segment = ''; }
                if($segment[0] == ':'){
                    $params[substr($segment, 1)] = $indexer;
                    $indexer++;
                }
                else {
                    $path[] = $segment;
                }
            }
        }
        $path = (empty($path)) ? "" : implode('/', $path);
        if(isset($path[0]) && $path[0] == '/'){
            $path = substr($path, 1);
        }
        else if($path == ''){
            $path = '/';
        }
		if( !array_key_exists( $this->_base . $path, $this->_routes[$type])){
            $this->_routes[$type][$this->_base . $path] = [
                "base" => $this->_base . $path ,
                "type" => $type ,
                "params" => $params,
                "responder" => $responder
            ];
            $this->_raw_routes[$type][$this->_base . $path] = [
                "base" => $this->_base . $path ,
                "type" => $type ,
                "params" => $params,
            ];
		    return true;
        }
		else{
		    return false;
        }
	}

	public function trace(bool $toTrace = true){
	    $this->isTrace = $toTrace;
    }

	public function getTrace(){
	    return  [
	        'routes' => array_filter($this->_raw_routes),
            'base' => $this->_base,
            'request_blocks' => $this->_request_blocks,
            'request_uri' => implode('/', $this->_request_blocks),
            'request_type' => $_SERVER['REQUEST_METHOD']
        ];
    }

	public function run($request){
        header('Content-Type: application/json');
        $request = ($request == '') ? '/' : $request;
        $type = $_SERVER['REQUEST_METHOD'];
        $blocks = explode('/', $request);
        if($blocks[sizeof($blocks) - 1] == NULL ){
            unset($blocks[sizeof($blocks) -1 ]) ;
        }
        $found = false;
        $route = null;
        if(sizeof($blocks) == 1 && $blocks[0] == ""){
            $blocks[0] = '/';
        }
        for($counter = 1;$counter<=sizeof($blocks);$counter++){
            $temp = array_slice($blocks, 0, $counter);
            $temp = implode('/', $temp);
            if( !$found  && array_key_exists($temp, $this->_routes[$type]) ){
                $route = $this->_routes[$type][$temp];
                $found = true;
            }
            else if($found){
                $counter--;
                foreach($route['params'] as $key => $index) {
                    $route['params'][$key] = isset($blocks[$counter]) ? $blocks[$counter] : null;
                    $counter++;
                }
                break;
            }
            else if(!$found && !array_key_exists($temp, $this->_routes[$type])){
                foreach($this->_supported_methods as $supported_type){
                    if( array_key_exists($temp, $this->_routes[$supported_type]) ){
                        $this->triggered[] = 405;
                        break;
                    }
                }
            }
        }
        $this->_request_blocks = $blocks;
        if($this->isTrace){
            $this->_result['track'] = $this->getTrace();
        }
        if( $route != null ){
            $_POST = json_decode(file_get_contents('php://input'), true);
            if(empty($route['params'])){
                $this->_result['result'] = $route['responder']();
            }
            else {
                $this->_result['result'] = $route['responder']($route['params']);
            }
        }
        else if(empty($this->triggered)){
            array_push($this->triggered, 404);
        }
        if(!empty($this->triggered)){
            $this->_result['error'] = [];
            foreach($this->triggered as $code){
                switch($code){
                    case 404:
                        $this->_result['error'][] = $this->_404_responder;
                        break;
                    case 405:
                        $this->_result['error'][] = $this->_405_responder;
                        break;
                    default:
                        break;
                }
            }
        }
        return (json_encode($this->_result));
    }

    public function segment(){
	    return $this->_request_blocks;
    }

    public function set404(array | atring $response): bool{
	    if($response == null or $response == ''){
	        return false;
        }
	    $this->_404_responder = $response;
	    return true;
    }
    public function set405(array | atring $response): bool{
        if($response == null or $response == ''){
            return false;
        }
        $this->_405_responder = $response;
        return true;
    }
}

function debug($val){
    echo "<pre>";
    print_r($val);
    echo "</pre>";
}