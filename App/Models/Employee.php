<?php

class Employee {
    private $conn;
    private $table = "employees";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all employees
    public function index() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch single employee by ID
    public function get($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new employee
    public function store($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (first_name, last_name, middle_initial, mobile_number, email, sex, job_title) 
                  VALUES (:first_name, :last_name, :middle_initial, :mobile_number, :email, :sex, :job_title)";

        $stmt = $this->conn->prepare($query);

        $middleInitial = !empty($data['middle_initial']) ? strtoupper(substr(trim($data['middle_initial']), 0, 1)) : null;

        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":last_name", $data['last_name']);
        $stmt->bindParam(":middle_initial", $middleInitial);
        $stmt->bindParam(":mobile_number", $data['mobile_number']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":sex", $data['sex']);
        $stmt->bindParam(":job_title", $data['job_title']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Update employee
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET first_name = :first_name, 
                      last_name = :last_name, 
                      middle_initial = :middle_initial, 
                      mobile_number = :mobile_number, 
                      email = :email, 
                      sex = :sex, 
                      job_title = :job_title 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $middleInitial = !empty($data['middle_initial']) ? strtoupper(substr(trim($data['middle_initial']), 0, 1)) : null;

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":last_name", $data['last_name']);
        $stmt->bindParam(":middle_initial", $middleInitial);
        $stmt->bindParam(":mobile_number", $data['mobile_number']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":sex", $data['sex']);
        $stmt->bindParam(":job_title", $data['job_title']);

        return $stmt->execute();
    }

    // Delete employee
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}