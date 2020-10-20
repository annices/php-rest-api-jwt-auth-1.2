<?php
// To be able to check sessions:
session_start();

// Check if user is logged in, else redirect to login page:
if (!isset($_SESSION['email'])) {
	header('Location: login.php');
}

// Include necessary file:
include_once "objects/file.php";

// Instantiate file object:
$file = new File();

// Get API urls from settings file:
$apiurl = $file->parse_file("config/settings.ini");
$apigetuser = $apiurl['API_GetUserURL'];
$apiupdateuser = $apiurl['API_UpdateUserURL'];

// Request latest values to edit from external server:
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $apigetuser);
$result = curl_exec($ch);
curl_close($ch);

// Decode fetched data from JSON string to php variables:
$obj = json_decode($result);
$firstname = $obj->firstname;
$lastname = $obj->lastname;
$email = $obj->email;

// If the user has hit the save button:
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// Catch the form inputs to be posted to the external server:
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	// Include necessary files:
	include_once 'config/dbaccess.php';
	include_once 'objects/token.php';

	// Establish a db connection:
	$database = new Database();
	$db = $database->getConnection();

	// Instantiate a token object:
	$token = new Token($db);

	// Get latest stored JWT to attach with the post:
	$jwt = $token->getJWT();

	// If inputs are valid:
	if(strlen($firstname) != 0 && strlen($lastname) != 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {

    	// Open a cURL session to be able to reach the external API:
		$ch = curl_init($apiupdateuser);

    	// Map the form data to the right JSON object parameters:
		$jsonData = array(
			'firstname' => $firstname,
			'lastname' => $lastname,
			'email' => $email,
			'password' => $password,
			'jwt' => $jwt
		);

    	// Encode form data from php variables to JSON string:
		$jsonDataEncoded = json_encode($jsonData);

    	// Tell cURL that we want to send a POST request:
		curl_setopt($ch, CURLOPT_POST, 1);

    	// Attach the encoded JSON string to the POST fields:
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    	// Set the content type to application/json:
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

		// Prevent the json response msg to be printed on the webpage (but keep msg to be displayed when testing the API via Postman):
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    	// Execute the request to the external API:
		$result = curl_exec($ch);

		// Get status code from external server:
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		// Close cURL session, and free up system resources:
		curl_close($result);

		// If everything went OK:
		if($httpCode == 200) {
			$feedback = "<span class='successmsg'>The user details were successfully saved to the server 2 app.</span>";
		}

	}
	else {
		$feedback = "<span class='errormsg'>Please fill in the manditory fields correctly.</span>";
	}

}

include_once 'layout/header.php';

?>

<h1>Update user details</h1>

<?php
// Print feedback is there is any:
if ($feedback != "") {
	echo "<p class='feedback'>$feedback</p>";
}
// Show logout link if user is logged in:
if (isset($_SESSION['email'])) {
	echo '<p><b><a href="logout.php">Logout</a></b></p>';
}
?>

<div id="container">

	<form action="update_user.php" method="post">
		<div class="row">
			<div class="col-25">
				Firstname: <span style="color: red">*</span>
			</div>
			<div class="col-75">
				<input name="firstname" type="text" size="50" value="<?php echo $firstname; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-25">
				Lastname: <span style="color: red">*</span>
			</div>
			<div class="col-75">
				<input name="lastname" type="text" size="50" value="<?php echo $lastname; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-25">
				Email: <span style="color: red">*</span>
			</div>
			<div class="col-75">
				<input name="email" type="email" size="50" value="<?php echo $email; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-25">
				Password:
			</div>
			<div class="col-75">
				<input name="password" type="text" size="50" value="<?php echo $password; ?>"><br />
				<em>(If you leave the password field empty, you will keep your current password on save.)</em>
			</div>
		</div>
		<div class="row">
			<input type="submit" value="Save" />
		</div>
	</form>

</div>

<?php include_once 'layout/footer.php'; ?>