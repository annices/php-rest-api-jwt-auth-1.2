<?php

// Resume existing session:
session_start();

// Destroy current session:
unset($_SESSION['email']);

// Include necessary file:
include_once "objects/file.php";

// Instantiate file object:
$file = new File();

// Get API url from settings file:
$apiurl = $file->parse_file("config/settings.ini");
$apilogout = $apiurl['API_LogoutURL'];

// Open a cURL session to reach the external API:
$ch = curl_init($apilogout);

// Tell server 2 we want to clear the user email:
$jsonData = array(
	'email' => ''
);

// Encode form data from php variable to JSON string:
$jsonDataEncoded = json_encode($jsonData);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

// Get status code from external server:
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close cURL session, and free up system resources:
curl_close($result);

// Redirect to logged out page:
header('Location: login.php');
