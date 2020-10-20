<?php
// Required headers:
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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

// Catch posted data from the external server:
$data = json_decode(file_get_contents("php://input"));

// Apply posted data on the user object:
$user->email = $data->email;
$email_exists = $user->emailExists($user->email);
$email = $data->email;

// Get API url from settings file:
$apiurl = $file->parse_file("../config/settings.ini");
$url = $apiurl['API_TokenURL'];

// Write fetched user email to settings file for later use:
$configs = $file->edit_file("email",$email."\x20","../config/settings.ini");

// Generate a JWT (JSON Web Token):
include_once '../config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// Check valid login credentials:
if($email_exists && password_verify($data->password, $user->password)) {

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
  $ch = curl_init($url);

  // Map the form data to the right JSON object parameters:
  $jsonData = array(
    'statuscode' => '200',
    'jwt' => $jwt
  );

  // Encode data from php variables to JSON string:
  $jsonDataEncoded = json_encode($jsonData);

  // Tell cURL that we want to send a POST request:
  curl_setopt($ch, CURLOPT_POST, 1);

  // Attach the encoded JSON string to the POST fields:
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

  // Set the content type to application/json:
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

  // Execute the request to the external API:
  $result = curl_exec($ch);

  // Also, print success message and generated jwt:
  echo json_encode(
    array(
      "message" => "Successful login.",
      "jwt" => $jwt
    )
  );

}

// Else when login fails:
else{

  // Set error response code:
  http_response_code(401);

  // Print error message to end usuer:
  echo json_encode(
      array(
        "message" => "Login failed.",
    )
  );
}
?>