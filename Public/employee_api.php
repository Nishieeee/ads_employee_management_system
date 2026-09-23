<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle pre-flight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../App/Controllers/EmployeeController.php';

$database = new Database();
$db = $database->getConnection();

$controller = new EmployeeController($db);
$method = $_SERVER['REQUEST_METHOD'];

// Get ID if present in query string (?id=1)
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Read JSON body for POST, PUT, or DELETE
$rawBody = file_get_contents("php://input");
$data = json_decode($rawBody, true) ?? [];

// If ID wasn't in URL query parameter, check if it was provided in the JSON payload
if (!$id && isset($data['id'])) {
    $id = (int)$data['id'];
}

switch ($method) {
    case 'GET':
        if ($id) {
            $controller->show($id);
        } else {
            $controller->index();
        }
        break;

    case 'POST':
        $controller->store($data);
        break;

    case 'PUT':
        $controller->update($id, $data);
        break;

    case 'DELETE':
        $controller->destroy($id);
        break;

    default:
        http_response_code(405);
        echo json_encode([
            "status" => "error",
            "message" => "Method not allowed."
        ]);
        break;
}
