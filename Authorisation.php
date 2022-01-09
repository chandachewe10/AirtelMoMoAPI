<?php
require_once "vendor/autoload.php";
  
use GuzzleHttp\Client;
  
$client = new Client();



//Enter Client_id
$client_id = "*********************************";

//Enter Client_secret
$client_secret = "*******************************";

//Grant Type
$grant_type = "client_credentials";





/*
This API is used to get the bearer token. The output of this API contains access_token that will
be used as bearer token for the API that we will be going to call.
*/



//Check for errors while fetching the token 
try{

$response = $client->request('POST', 'https://openapiuat.airtel.africa/auth/oauth2/token',[
    "headers" => [
        "Content-Type" => "application/json"
    ],
   "json"=>[ "client_id" => "$client_id",
      "client_secret" => "$client_secret",
      "grant_type" => "$grant_type",
   ]
]);



    $body = $response->getBody();
    $token = json_decode($body);
    $access_token = $token->access_token;
    
}  

//Throw Errors Here if any
catch(Exception $e) {
    echo 'Whoops something went wrong: ' .$e->getMessage();
  }