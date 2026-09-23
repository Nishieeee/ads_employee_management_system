<?php

class Employee {
    private $conn;
    private $table = "employees";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all employees
    public function getAllEmployees() {
        $query = "SELECT id, first_name, last_name, middle_initial, mobile_number, email, sex, job_title FROM " . $this->table . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch employee by ID
    public function getEmployeeById($id) {
        $query = "SELECT id, first_name, last_name, middle_initial, mobile_number, email, sex, job_title FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new employee
    public function addEmployee($first_name, $last_name = null, $middle_initial = null, $mobile_number = null, $email = null, $sex = null, $job_title = null) {
        if (is_array($first_name)) {
            $data = $first_name;
            $first_name = $data['first_name'] ?? null;
            $last_name = $data['last_name'] ?? null;
            $middle_initial = $data['middle_initial'] ?? null;
            $mobile_number = $data['mobile_number'] ?? null;
            $email = $data['email'] ?? null;
            $sex = $data['sex'] ?? null;
            $job_title = $data['job_title'] ?? null;
        }

        $mi = !empty($middle_initial) ? strtoupper(substr(trim($middle_initial), 0, 1)) : null;

        $query = "INSERT INTO " . $this->table . " (first_name, last_name, middle_initial, mobile_number, email, sex, job_title) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$first_name, $last_name, $mi, $mobile_number, $email, $sex, $job_title]);
    }

    // Update employee details
    public function updateEmployee($id, $first_name, $last_name = null, $middle_initial = null, $mobile_number = null, $email = null, $sex = null, $job_title = null) {
        if (is_array($first_name)) {
            $data = $first_name;
            $first_name = $data['first_name'] ?? null;
            $last_name = $data['last_name'] ?? null;
            $middle_initial = $data['middle_initial'] ?? null;
            $mobile_number = $data['mobile_number'] ?? null;
            $email = $data['email'] ?? null;
            $sex = $data['sex'] ?? null;
            $job_title = $data['job_title'] ?? null;
        }

        $mi = !empty($middle_initial) ? strtoupper(substr(trim($middle_initial), 0, 1)) : null;

        $query = "UPDATE " . $this->table . " SET first_name = ?, last_name = ?, middle_initial = ?, mobile_number = ?, email = ?, sex = ?, job_title = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$first_name, $last_name, $mi, $mobile_number, $email, $sex, $job_title, $id]);
    }

    // Delete employee
    public function deleteEmployee($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // Backward compatibility aliases
    public function index() { return $this->getAllEmployees(); }
    public function get($id) { return $this->getEmployeeById($id); }
    public function store($data) { return $this->addEmployee($data); }
    public function update($id, $data) { return $this->updateEmployee($id, $data); }
    public function delete($id) { return $this->deleteEmployee($id); }
}