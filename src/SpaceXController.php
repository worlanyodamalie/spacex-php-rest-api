<?php
namespace Src;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class SpaceXController {

    private $client = null;
    // private $body;

    public function __construct(){
        $this->client = new Client([
            'base_uri' => 'https://api.spacexdata.com/v4/'
        ]);

        // $this->body = $body;
    }


    public function capsules($uri){
        
        $body = file_get_contents("php://input");
        $headers = [
            'Content-Type' => 'application/json'
          ];
        //print_r($body);

        try {
            $request = new Request('POST', 'capsules/query' , $headers ,$body);
            $res = $this->client->sendAsync($request)->wait();
            //echo $res->getBody();
            return $res->getBody(); 
        } catch (RequestException $e) {
           echo $e;
        }
       
    }

    public function rockets($uri){
       
        // print_r($this->body);
        
        // try {
        //     $request = new Request('GET', 'rockets');
        //     $res = $this->client->sendAsync($request)->wait();
        //     echo $res->getBody();
        //     return $res->getBody(); 
        // } catch (RequestException $e) {
        //    echo $e;
        // }
       
    }
}