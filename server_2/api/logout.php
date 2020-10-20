<?php
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include necessary file:
include_once "../objects/file.php";
$file = new File();

// Catch posted data from the external server:
$data = json_decode(file_get_contents("php://input"));

// Clear user email from file:
$configs = $file->edit_file("email",$data->email."\x20","../config/settings.ini");

// Set response code when ok:
http_response_code(200);

?>