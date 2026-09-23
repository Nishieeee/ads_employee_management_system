<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../Config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Database connected successfully."
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to connect to the database."
    ]);
}
