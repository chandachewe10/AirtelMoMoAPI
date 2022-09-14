<?php
require_once "vendor/autoload.php";
require_once "Authorisation.php"; 
use GuzzleHttp\Client;



 
/*
This API is used to request a payment from a consumer(Payer).
The consumer(payer) will be asked to authorize the payment.
After authorization, the transaction will be executed.
*/


$client = new Client();


//Enter phone number here without the country code
$phone_number = "97*****9";

//Country Code
$country_code = "ZM";

//Currency Code
$currency_code = "ZMW";

//Amount to be sent 
$amount = "20";




//Check for errors while executing the code 
try{

$response = $client->request('POST', 'https://openapiuat.airtel.africa/merchant/v1/payments/',[
    "headers" => [
        "Content-Type" => "application/json",
        "Authorization" => "Bearer ".$access_token, 
        "X-Country" => "$country_code",
        "X-Currency" => "$currency_code", 
    ],
    
   "json"=>[ 
    
        "reference" => "Testing transaction",
        "subscriber" => [
          "country" => "$country_code",
          "currency" => "$currency_code",
          "msisdn" => "$phone_number"
        ],
        "transaction" => [
          "amount" => $amount,
          "country" => "$country_code",
          "currency" => "$currency_code",
          "id" => mt_rand(1000000, 9999999),
        ]
  
   ]
]);



    $push_payments = $response->getBody();
    echo $push_payments;
    
}  

//Throw Error Here
catch(Exception $e) {
    echo 'Whoops something went wrong: ' .$e->getMessage();
  }
