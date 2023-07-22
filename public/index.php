<?php
require "../bootstrap.php";
use Src\SpaceXController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER["REQUEST_METHOD"];
$uriParts = explode( '/', $uri );

$routes = [
    'capsules' => [
        'method' => 'POST',
        'expression' => '/^\/capsules\/?$/',
        'controller_method' => 'capsules'

    ],
    'capsule' => [
        'method' => 'GET',
        'expression' => '/^\/capsule\/?$/',
        'controller_method' => 'capsule'

    ],
    'rockets' => [
        'method' => 'POST',
        'expression' => '/^\/rockets\/?$/',
        'controller_method' => 'rockets'

    ],
    ];

$routeFound = null;
    
foreach ($routes as $route) {
        if ($route['method'] == $requestMethod &&
            preg_match($route['expression'], $uri))
        {
            $routeFound = $route;
            break;
        }
    }  
    
    if (! $routeFound) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    
    $methodName = $route['controller_method'];

    // $body = null;

    // if(isset($uri[2])){
    //     $body = $_SERVER;
    // }

    $controller = new SpaceXController();
    $controller->$methodName($uri);
