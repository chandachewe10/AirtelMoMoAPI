<?php
require_once "vendor/autoload.php";
require_once "Authorisation.php"; 
use GuzzleHttp\Client;

/* 
RSA PASSWORD ENCRYPTION WITH PADDING OPENSSL_PKCS1_PADDING 1024
BITS 64BIT ENCODED
*/

//Type of padding
$padding = OPENSSL_PKCS1_PADDING;

//4 digit Pin from Customer/Client
$sensitiveData = "1111";

// Get keys from a string. Public key for encrypting from the Documentation 
$publicKeyString = <<<PK
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCkq3XbDI1s8Lu7SpUBP+bqOs/MC6PKWz6n/0UkqTiOZqKqaoZClI3BUDTrSIJsrN1Qx7ivBzsaAYfsB0CygSSWay4iyUcnMVEDrNVOJwtWvHxpyWJC5RfKBrweW9b8klFa/CfKRtkK730apy0Kxjg+7fF0tB4O3Ic9Gxuv4pFkbQIDAQAB
-----END PUBLIC KEY-----
PK;

// Load public key
$publicKey = openssl_pkey_get_public(array($publicKeyString, ""));
if (!$publicKey) {
    echo "Public key NOT Correct
";
}
if (!openssl_public_encrypt($sensitiveData, $encryptedWithPublic, $publicKey,$padding)) {
    echo "Error encrypting with public key
";
}

//Encrypted Pin
$pinEncrypted = base64_encode($encryptedWithPublic); 
// 


 
/*
This API is used to request a payment from a consumer(Payer).
The consumer(payer) will be asked to authorize the payment.
After authorization, the transaction will be executed.
*/


$client = new Client();


//Enter phone number here without the country code
$phone_number = "97***12";

//Country Code
$country_code = "ZM";

//Currency Code
$currency_code = "ZMW";

//Amount to be sent 
$amount = "1";




//Check for errors while executing the code 
try{

$response = $client->request('POST', 'https://openapiuat.airtel.africa/standard/v1/disbursements/',[
    "headers" => [
        "Content-Type" => "application/json",
        "Authorization" => "Bearer ".$access_token, 
        "X-Country" => "$country_code",
        "X-Currency" => "$currency_code", 
    ],
    
   "json"=>[ 

    "payee" => [
        "msisdn" => $phone_number
    ],
      "reference" => "Disbursements",
      "pin" => "$pinEncrypted",
      "transaction" => [
        "amount" => $amount,
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