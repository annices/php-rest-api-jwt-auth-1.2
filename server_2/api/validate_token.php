<?php
// Required headers:
header("Access-Control-Allow-Origin: http://localhost/rest-api-auth/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include necessary files to decode JWT (JSON Web Token):
include_once '../config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// Catch posted data from external system:
$data = json_decode(file_get_contents("php://input"));
 
// Get JWT:
$jwt=isset($data->jwt) ? $data->jwt : "";

// Check if there is a JWT to decode:
if($jwt) {
 
    // If decoding succeeds, show user details:
    try {
        // Decode JWT:
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
        // Set response code when ok:
        http_response_code(200);
 
        // Print success message and show user details:
        echo json_encode(array(
            "message" => "Access granted.",
            "data" => $decoded->data
        ));
 
    }

// Handle decoding failure when JWT is invalid:
catch (Exception $e) {
 
    // Set error response code:
    http_response_code(401);
 
    // Print error message to end user:
    echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
}

}

// Display error message if JWT is missing:
else{
 
    // Set response code:
    http_response_code(401);
 
    // Print error message to end user:
    echo json_encode(array("message" => "Access denied."));
}
?>