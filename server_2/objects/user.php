<?php
// 'User' object class:
class User {

    // Variables holding db connection and db table name:
    private $conn;
    private $table_name = "a_user";

    // Create object properties:
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    // Constructor:
    public function __construct($db){
        $this->conn = $db;
    }

    /*
    Method to create a new user record in db.
    */
    function create() {

        // Create an insert SQL query:
        $query = "INSERT INTO " . $this->table_name . "
        SET
        firstname = :firstname,
        lastname = :lastname,
        email = :email,
        password = :password";

        // Prepare the query for execution to db:
        $stmt = $this->conn->prepare($query);

        // Sanitize values to prevent unallowed characters to be stored in db:
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));

        // Bind the values to map/store posted data to the right db columns:
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        // Encrypt the password before saving it to db:
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        // Execute the query and check if it succeeded:
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    /*
    Method to check if given email exists in db.
    */
    function emailExists() {

        // Query to check if email exists:
        $query = "SELECT id, firstname, lastname, password
        FROM " . $this->table_name . "
        WHERE email = ?
        LIMIT 0,1";

        // Prepare the query for execution to db:
        $stmt = $this->conn->prepare( $query );

        // Sanitize values to prevent unallowed characters to be stored in db:
        $this->email=htmlspecialchars(strip_tags($this->email));

        // Bind posted email to query:
        $stmt->bindParam(1, $this->email);

        // Execute the query to db:
        $stmt->execute();

        // See if there is a matching db table row for the given email:
        $num = $stmt->rowCount();

        // If email exists (matching row), assign object with matching row values for easy access and use for php sessions:
        if($num > 0) {

            // Get record details/values from db:
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Assign values to object properties:
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];

            // Return true because email exists in db:
            return true;
        }

        // Return false if email does not exist in db:
        return false;
    }

    /* 
    Method to update a user record in db.
    */
    public function update() {

        // If password is requested to be updated:
        $password_set=!empty($this->password) ? ", password = :password" : "";

        // If no posted password, leave current password as-is:
        $query = "UPDATE " . $this->table_name . "
        SET
        firstname = :firstname,
        lastname = :lastname,
        email = :email
        {$password_set}
        WHERE id = :id";

        // Prepare the query for execution to db:
        $stmt = $this->conn->prepare($query);

        // Sanitize values to prevent unallowed characters to be stored in db:
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));

        // Bind the values from the form to be stored in the right db table columns:
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        // Encrypt the password before saving to db:
        if(!empty($this->password)) {
            $this->password=htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        // Unique ID of record (table row/tuple) to be edited:
        $stmt->bindParam(':id', $this->id);

        // Execute the query to db:
        if($stmt->execute()) {
            return true;
        }

        // Else do nothing:
        return false;
    }


    /*
    Method to get the latest user details by its ID for editing/updating purpose.
    */
    function getUser() {

        // Query to check if user record exists in db:
        $query = "SELECT id, firstname, lastname, email
        FROM " . $this->table_name . "
        WHERE email = :email";

        // Prepare the query for db execution:
        $stmt = $this->conn->prepare($query);

        // Bind posted email to the right db table column:
        $stmt->bindParam(':email', $this->email);

        // Execute the query:
        $stmt->execute();

        // Get the total number of table rows:
        $num = $stmt->rowCount();

        // If the email matches a table row, fetch its values to assign to a user object:
        if($num > 0) {

            // Get record details/values:
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Assign values to object properties:
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->email = $row['email'];

            return true;

            // Close database connection when done:
            $this->connection = null;
        }
        return false;

    }

} // End class.