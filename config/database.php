<?php 
  class Database {
    // DB Params
    private $host = 'localhost';
    private $db_name = 'jorgetat_ce_system';
    private $username = 'jorgetat_ce_admin';
    private $password = '.(Ywa{U6C0zg';
    private $conn;

    // DB Connect
    public function connect() {
      $this->conn = null;

      try { 
        $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
    
  }

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();