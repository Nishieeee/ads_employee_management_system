<?php 

class Employee {
    private $conn;
    private $table = "employees";

    public function __construct($db) {
        $this->conn = $db;
    }

    // fetch employee
    public function get($id) {
        return;
    }

    // get all employees
    public function index() {

        // query to db
        $query = "SELECT * FROM " . $this->table;

        // load query
        $stmt = $this->conn->prepare($query);

        // execute query
        if($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    // create new employees
    public function store() {
        return;
    }

    // update employee
    public function update() {
        return;
    }

    // delete TODO: create soft delete method
    public function delete() {
        return;
    }
} 