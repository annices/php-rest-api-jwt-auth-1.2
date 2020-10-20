<?php
// To be able to handle session variables:
session_start();

// Include necessary file:
include_once "objects/file.php";

// Instantiate file object:
$file = new File();

// Get API url from settings file:
$apiurl = $file->parse_file("config/settings.ini");
$apilogin = $apiurl['API_LoginURL'];

// If the user has hit the submit button:
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Catch the form values to be posted to the external server:
	$email = $_POST['email'];
	$password = $_POST['password'];

    // Open a cURL session to reach the external API:
	$ch = curl_init($apilogin);

    // Map the form data to the right JSON object parameters:
	$jsonData = array(
		'email' => $email,
		'password' => $password
	);

    // Encode form data from php variable to JSON string:
	$jsonDataEncoded = json_encode($jsonData);

    // Tell cURL that we want to send a POST request:
	curl_setopt($ch, CURLOPT_POST, 1);

    // Attach the encoded JSON string to the POST fields:
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    // Set the content type to application/json:
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	// Prevent the response msg from the other server to be printed on the webpage (it will still be displayed when testing the API via Postman):
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request to the external API:
	$result = curl_exec($ch);

	// Get status code from external server:
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	// Close cURL session, and free up system resources:
	curl_close($result);

	// If everything went OK:
	if($httpCode == 200) {

		// Set session on user email to login user:
		$_SESSION['email'] = $email;

		// Then redirect user to admin page:
		$redirect = "<script>window.location.href = 'update_user.php';</script>";
	}
	else {
		$feedback = "<span class='errormsg'>Invalid login details.</span>";
	}

}
?>

<?php include_once 'layout/header.php'; ?>

<h1>Login to update user details</h1>

<?php
// Print feedback if there is any:
if ($feedback != "") {
	echo "<p class='feedback'>$feedback</p>";
}
?>

<div id="container">

	<form method="post" action="login.php">
		<div class="row">
			<div class="col-25">
				Email: <span style="color: red">*</span>
			</div>
			<div class="col-75">
				<input name="email" type="email" size="50" value="<?php echo $email; ?>">
			</div>
		</div><div class="row">
			<div class="col-25">
				Password: <span style="color: red">*</span>
			</div>
			<div class="col-75">
				<input name="password" type="password" size="50" value="<?php echo $password; ?>">
			</div>
		</div><div class="row">
			<input type="submit" value="Login">
		</div>
	</form>

</div>

<?php echo $redirect; die; ?>

<?php include_once 'layout/footer.php'; ?>