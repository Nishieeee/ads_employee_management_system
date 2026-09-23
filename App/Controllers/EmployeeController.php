<?php

require_once __DIR__ . '/../Models/Employee.php';

class EmployeeController {
    private $employee;

    public function __construct($db) {
        $this->employee = new Employee($db);
    }

    // GET /Public/employee_api.php
    public function index() {
        $employees = $this->employee->index();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "count" => count($employees),
            "data" => $employees
        ]);
    }

    // GET /Public/employee_api.php?id={id}
    public function show($id) {
        if (!$id) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Employee ID is required."
            ]);
            return;
        }

        $employee = $this->employee->get($id);

        if ($employee) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $employee
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Employee with ID {$id} not found."
            ]);
        }
    }

    // POST /Public/employee_api.php
    public function store($data) {
        // Validate required fields
        $requiredFields = ['first_name', 'last_name', 'mobile_number', 'email', 'sex', 'job_title'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Field '{$field}' is required."
                ]);
                return;
            }
        }

        try {
            $newId = $this->employee->store($data);
            if ($newId) {
                http_response_code(201);
                echo json_encode([
                    "status" => "success",
                    "message" => "Employee created successfully.",
                    "id" => (int)$newId
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed to create employee record."
                ]);
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Database error: " . $e->getMessage()
            ]);
        }
    }

    // PUT /Public/employee_api.php?id={id}
    public function update($id, $data) {
        if (!$id) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Employee ID is required."
            ]);
            return;
        }

        // Check if employee exists
        $existing = $this->employee->get($id);
        if (!$existing) {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Employee with ID {$id} not found."
            ]);
            return;
        }

        // Merge updated fields with existing data if some fields are not provided
        $mergedData = [
            'first_name' => $data['first_name'] ?? $existing['first_name'],
            'last_name' => $data['last_name'] ?? $existing['last_name'],
            'middle_initial' => array_key_exists('middle_initial', $data) ? $data['middle_initial'] : $existing['middle_initial'],
            'mobile_number' => $data['mobile_number'] ?? $existing['mobile_number'],
            'email' => $data['email'] ?? $existing['email'],
            'sex' => $data['sex'] ?? $existing['sex'],
            'job_title' => $data['job_title'] ?? $existing['job_title']
        ];

        try {
            if ($this->employee->update($id, $mergedData)) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Employee updated successfully."
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed to update employee record."
                ]);
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Database error: " . $e->getMessage()
            ]);
        }
    }

    // DELETE /Public/employee_api.php?id={id}
    public function destroy($id) {
        if (!$id) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Employee ID is required."
            ]);
            return;
        }

        // Check if employee exists
        $existing = $this->employee->get($id);
        if (!$existing) {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Employee with ID {$id} not found."
            ]);
            return;
        }

        if ($this->employee->delete($id)) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "Employee deleted successfully."
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Failed to delete employee record."
            ]);
        }
    }
}
