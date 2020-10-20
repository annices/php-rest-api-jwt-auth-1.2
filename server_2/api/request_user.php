<?php
// Include necessary files:
include_once '../config/dbaccess.php';
include_once '../objects/user.php';
include_once '../objects/file.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$file = new File();

// Get logged in user email from settings file:
$getemail = $file->parse_file('../config/settings.ini');
$user->email = $getemail['email'];

// Get user details from db only for permitted user:
if($user->getUser()) {
    
    $firstname = $user->{'firstname'};
    $lastname = $user->{'lastname'};
    $email = $user->{'email'};

  	// Set response code when ok:
	http_response_code(200);

	// Prepare JSON data to be sent back:
	echo json_encode(
		array(
			"firstname" => $firstname,
			"lastname" => $lastname,
			"email" => $email
		)
	);

}
else {
    // Print feedback:
	echo json_encode(
		array(
			"message" => "Could not get data."
		)
	);
}
?>