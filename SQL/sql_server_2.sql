--
-- Execute the SQL code below to your MySQL supported database to create the user DB table
-- on your "server 2". This is to be able to store and update the user credentials.
--

CREATE TABLE a_user (
	id int AUTO_INCREMENT PRIMARY KEY,
	firstname varchar(256) NOT NULL,
	lastname varchar(256) NOT NULL,
	email varchar(256) NOT NULL,
	password varchar(2048) NOT NULL,
	created datetime NOT NULL DEFAULT current_timestamp(),
	modified timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	UNIQUE KEY(email)
);