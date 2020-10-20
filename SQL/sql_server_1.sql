--
-- Execute the SQL code below to your MySQL supported database to create the token DB table
-- on your "server 1". This is to be able to store and update JSON Web Tokens, which is used
-- to check permission to update the user credentials on server 2.
--

CREATE TABLE a_token (
	id int AUTO_INCREMENT PRIMARY KEY,
	jwt varchar(1000) NOT NULL,
	created datetime NOT NULL DEFAULT current_timestamp(),
	modified timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
);