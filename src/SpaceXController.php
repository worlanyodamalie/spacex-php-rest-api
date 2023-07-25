<?php
namespace Src;
require "../bootstrap.php";
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class SpaceXController {

    private $client = null;
    private $secret;
    private $issuedAt ;
    private $expire ;

    public function __construct(){
        $this->client = new Client([
            'base_uri' => 'https://api.spacexdata.com/v4/'
        ]);

        date_default_timezone_set('Africa/Accra');
        $this->issuedAt = time();
        $this->expire = $this->issuedAt + 3600;
        $this->secret = "secret_used_for_test_not_for_production";

        // $this->body = $body;
    }

    public function encode($iss, $data)
    {
        // echo($this->secrect);

        $token = array(
            "iss" => $iss,
            "aud" => $iss,
            "iat" => $this->issuedAt,
            "exp" => $this->expire,
            "data" => $data
        );

        return JWT::encode($token, $this->secret, 'HS256');
    }

    public function decode($token)
    {
        try {
            $decode = JWT::decode($token, new Key($this->secret, 'HS256'));
            // echo "Inside decode";
            // print_r($decode);
            return $decode;
        } catch (Exception $e) {
            
            return $e->getMessage();
        }
    }

    public function generateToken(){
        header("Content-Type: application/json");
        $payload = "token payload,this should not be used in production";
        $iss = $_ENV['TOKEN_ISSUER'];
        $token = $this->encode($iss , $payload);
        
        
        $response = array();
        $response["token"] = $token; 
       
        $data = json_encode($response,true);
        echo $data;
        return $data;
    }


    public function capsules($uri){
        $body = null;
        if(is_object(file_get_contents("php://input"))){
            $body = file_get_contents("php://input");
        }
        
        //echo $body;
        $headers = [
            'Content-Type' => 'application/json; charset=UTF-8',
            // 'Access-Control-Allow-Origin' =>  '*',
            // "Access-Control-Allow-Methods" => "OPTIONS,GET,POST,PUT,DELETE",
            // "Access-Control-Max-Age" => "86400",
            // "Access-Control-Allow-Headers" => "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" ,
            // "HTTP/1.0 200 OK"
          ];
       //print_r($body);
       //return  header("HTTP/1.0 200 OK");
       

        try {
            $request = new Request('POST', 'capsules/query' , $headers ,$body);
            $res = $this->client->sendAsync($request)->wait();
            
           
            echo $res->getBody();
            return $res->getBody();
           
        } catch (RequestException $e) {
           echo $e;
        }
       
    }

    public function rockets($uri){
       
        // print_r($this->body);
        
        try {
            $request = new Request('GET', 'rockets');
            $res = $this->client->sendAsync($request)->wait();
            echo $res->getBody();
            return $res->getBody(); 
        } catch (RequestException $e) {
           echo $e;
        }
       
    }
}