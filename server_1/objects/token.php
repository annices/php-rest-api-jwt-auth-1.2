<?php
// 'Token' object class:
class Token {

    // Variables holding db connection and db table name:
    private $conn;
    private $table_name = "a_token";

    // Create object properties:
    public $id;
    public $jwt;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    /*
    Method to create a JWT record in db.
    */
    function createJWT($jwt) {

        // Query to check if a token exists in db:
        $query = "SELECT id, jwt FROM " . $this->table_name . "
        LIMIT 0,1";

        // Prepare the query for execution to db:
        $stmt = $this->conn->prepare($query);

        // Execute the query to db:
        $stmt->execute();

        // If a token already exists, make an insert statement, else do an update:
        $num = $stmt->rowCount();

        if($num > 0) {

            // Get record details/values from db:
            $num = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $num['id'];
            $this->jwt = $jwt;

            // SQL query to update the token:
            $sql = "UPDATE " . $this->table_name . "
            SET jwt = :jwt
            WHERE id = :id";

            // Prepare the query for execution to db:
            $stmt = $this->conn->prepare($sql);

            // Bind the values from the form to be stored in the right db table columns:
            $stmt->bindParam(':jwt', $this->jwt);

            // Unique ID of record (table row/tuple) to be edited:
            $stmt->bindParam(':id', $this->id);

            // Execute the query and check if it succeeded:
            if($stmt->execute()) {
                return true;
            }
            // Else do nothing:
            return false;
        }
        else {

            // Create an insert SQL query:
            $query = "INSERT INTO " . $this->table_name . "
            SET jwt = :jwt";

            // Prepare the query for execution to db:
            $stmt = $this->conn->prepare($query);

            // Bind the values to map/store posted data to the right db columns (attributes):
            $stmt->bindParam(':jwt', $jwt);

            // Execute the query and check if it succeeded:
            if($stmt->execute()) {
                return true;
            }
            // Else do nothing:
            return false;
        }
    }

    /*
    Method to get the latest stored JSON Web Token to be attached with the user data that are requested to be updated on an external server.
    */
    function getJWT() {

        // Get stored token:
        $query = "SELECT *
        FROM " . $this->table_name . "
        LIMIT 0,1";

        // Prepare the query for execution to db:
        $stmt = $this->conn->prepare($query);

        // Execute the query to db:
        $stmt->execute();

        $num = $stmt->rowCount();

        $jwt = "";

        if($num > 0) {

            // Get JWT value from db:
            $num = $stmt->fetch(PDO::FETCH_ASSOC);

            // Assign value to object
            $jwt = $num['jwt'];
        }
        return $jwt;
    }

} // End class.