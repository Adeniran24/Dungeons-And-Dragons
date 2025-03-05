<?php
class Database {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("92.113.22.21", "u963849950_Adeniran", "#vxK0F&BU;s3", "u963849950_User");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

$db = new Database();
$conn = $db->getConnection();

?>