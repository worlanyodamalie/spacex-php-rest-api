<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    //header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header("Access-Control-Allow-Credentials: true");
    exit;
}
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
// header('Access-Control-Max-Age: 86400');
// header("Access-Control-Allow-Credentials: true");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// header("HTTP/1.0 200 OK");


require "../bootstrap.php";
use Src\SpaceXController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER["REQUEST_METHOD"];
$uriParts = explode( '/', $uri );

$controller = new SpaceXController();

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
    'generateToken' => [
        'method' => 'GET',
        'expression' => '/^\/generateToken\/?$/',
        'controller_method' => 'generateToken'
    ]
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
if($methodName != 'generateToken'){
    if(! authenticate($methodName)){
        header("HTTP/1.1 401 Unauthorized");
        exit("Unauthorized");
    }
}
    
// if(! authenticate($methodName)){
//     header("HTTP/1.1 401 Unauthorized");
//     exit("Unauthorized");
// }

    
$controller->$methodName($uri);

function authenticate($methodName){
    //print_r($_SERVER);
    $controller = new SpaceXController();
    if(! isset($_SERVER['HTTP_AUTHORIZATION']) ){
           return false;
       }

    preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'] , $matches );

    if(! isset($matches[1])) {
          return false;
       }


    $token = $matches[1];

    $tokenSections = explode('.',$token);

    $decoded_token =  $controller->decode($token);

    // print_r($tokenSections);
    // echo "Inside index";
    // print_r($decoded_token);

    if(! $decoded_token->iss === $_ENV['TOKEN_ISSUER']){
          return false;
    }

    return true;
    }
