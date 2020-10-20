<?php
// Required headers:
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include necessary files to encode JWT (JSON Web Token):
include_once '../config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// Include necessary files:
include_once '../config/dbaccess.php';
include_once '../objects/user.php';
include_once '../objects/file.php';

// Establish a db connection:
$database = new Database();
$db = $database->getConnection();

// Instantiate necessary objects:
$user = new User($db);
$file = new File();

// Get API url from settings file to request latest token from server 1:
$apigettoken = $file->parse_file('../config/settings.ini');
$apiurl = $apigettoken['API_TokenURL'];

// Catch posted data from external system:
$data = json_decode(file_get_contents("php://input"));

// Get JWT:
$jwt=isset($data->jwt) ? $data->jwt : "";

// If there is a JWT included in posted data:
if($jwt) {

  try {

    // Decode JWT:
    $decoded = JWT::decode($jwt, $key, array('HS256'));

    // Apply posted data on the user object:
    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->email = $data->email;
    $user->password = $data->password;
    $user->id = $decoded->data->id;

    // Update user details based on received data:
    if($user->update()) {

      // Re-generate JWT since the user details might be different:
      $token = array(
       "iss" => $iss,
       "aud" => $aud,
       "iat" => $iat,
       "nbf" => $nbf,
       "data" => array(
         "id" => $user->id,
         "firstname" => $user->firstname,
         "lastname" => $user->lastname,
         "email" => $user->email
       )
     );
      
      // Set response code when ok:
      http_response_code(200);

      // Generate a JWT with feedback message:
      $jwt = JWT::encode($token, $key);

      // Open a cURL session to be able to reach the external server:
      $ch = curl_init($apiurl);

      // Map the form data to the right JSON object parameters:
      $jsonData = array(
        'jwt' => $jwt
      );

      // Encode data to JSON format:
      $jsonDataEncoded = json_encode($jsonData);

      // Tell cURL that we want to send a POST request:
      curl_setopt($ch, CURLOPT_POST, 1);

      // Attach the encoded JSON string to the POST fields:
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

      // Set the content type to application/json:
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

      // Execute the request to the external API:
      $result = curl_exec($ch);

      // Send response in JSON format:
      echo json_encode(
        array(
          "message" => "User was updated.",
          "jwt" => $jwt
        )
      );

    }

    // Create feedback message when update failures:
    else{
      // Set error response code:
      http_response_code(401);

      // Display error message:
      echo json_encode(array("message" => "Unable to update user."));
    }

  }

  // Handle decoding failure when JWT is invalid:
  catch (Exception $e) {

    // Set response code:
    http_response_code(401);

    // Display error message to end user:
    echo json_encode(array(
      "message" => "Access denied.",
      "error" => $e->getMessage()
    ));
  }
}

// Print error message if JWT is empty:
else{

  // Set response code:
  http_response_code(401);

  // Print error message to end user:
  echo json_encode(array("message" => "Access denied."));
  
}

?>