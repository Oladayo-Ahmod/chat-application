<?php
    class Database{
        private $localhost = "localhost";
        private $username = "root";
        private $password = "";
        private $db_name = "chat";
        public $conn;
        function connect(){
            try {
                $this->conn = new mysqli($this->localhost,$this->username,$this->password,$this->db_name);
                return $this->conn;
            } catch (Exception $e) {
                 return "connection error".$e->getMessage();
            }
        }
    }
   
?>