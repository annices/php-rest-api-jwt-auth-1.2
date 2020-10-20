<?php
// Required headers to make sure we only receive JSON content:
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include necessary files:
include_once '../config/dbaccess.php';
include_once '../objects/token.php';

// Establish a db connection:
$database = new Database();
$db = $database->getConnection();

// Catch posted data from external system:
$data = json_decode(file_get_contents("php://input"));

// Instantiate a token object:
$token = new Token($db);

// Apply posted data on the token object:
$token->jwt = $data->jwt;

// Store the new JWT:
$token->createJWT($token->jwt);

?>