<?php
require_once('config.inc');

class DataBase {
    private $conection;
    public static $instance;
    private $server = SERVER;
    private $user = USER;
    private $password = PASSWORD;
    private $database = DATABASE;
    private $_query;
    private $result = array();
    private $counter = 0;

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new DataBase();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->connect();
    }
    
    private function connect() {
        $this->conection = new mysqli($this->server, $this->user, $this->password, $this->database);
        if($this->conection->connect_error) {
            die($this->conection->connect_error);
        }
    }

    public function lastInsertedId() {
        return $this->conection->insert_id;
    }

    private function disconnect() {
        $this->conection->close();
    }
    
    private function clon() {
        //trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
    }

    public function getError() {
        return $this->conection->error;
    }

    public function getConection() {
        return $this->conection;
    }
    
    public function execute($instruction) {
        $this->_query = $this->conection ->query($instruction);
        if($this->_query){
            if(is_object($this->_query)) {
                while($fila = $this->_query->fetch_assoc()) {
                    $this->result[] = $fila;
                }
                $this->counter = $this->_query->num_rows;
            }
        } else {
            die($this->conection->error);
            return false;
        }
        return $this;
    }

    public function getResult() {
        return $this->result;
    }

    public function count() {
        return $this->counter;
    }

    public function returnId(){
        return $this->conection->insert_id;
    }
/*
    public function getUser($username,$password) {
        $query = "SELECT * FROM customer WHERE username = '".$username."' and password = '".$password."'";
        $result = $this->execute($query);
        return $result;
    }

    public function isUser($username,$password) {
        $query = "SELECT * FROM customer WHERE username = '".$username."' and password = '".$password."'";
        $result = $this->execute($query);
        //var_dump($result);
        //if(is_object($result)){
            return $result;
        //}
        //return false;
    }
    */

    public function getFirst() {
        return $this->result[0];
    }
}
?>