<?php
// Set your default time-zone:
date_default_timezone_set('Europe/Berlin');
 
// The variables below are used by the JWT library to encode and decode a token. $key's value must be your own and unique secret key.
$key = "your_key"; // The value must be your own and unique secret key, name it to whatever.
$iss = "http://server2_address.com"; // ISS (issuer) claim identifies the principal that issued the JWT.
$aud = "http://server1_address.com"; // AUD (audience) claim identifies the recipients that the JWT is inteded for.
$iat = 1356999524; // IAT (issued at) claim identifies the time at which the JWT was issued.
$nbf = 1357000000; // NBF (not before) claim identifies the time before which the JWT MUST NOT be accepted for processing.

// You can read more about registered claims here: https://tools.ietf.org/html/rfc7519#section-4.1

?>
