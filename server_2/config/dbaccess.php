<?php
// Create class to be able to connect to the database on server 2:
class Database {
 
    // Specify your own database credentials below:
    private $host = "localhost";
    private $db_name = "server2_dbname";
    private $username = "server2_dbname";
    private $password = "server2_dbpassword";
    public $conn;
 
    // Method to call when you want to establish the database connection:
    public function getConnection() {
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
    
}