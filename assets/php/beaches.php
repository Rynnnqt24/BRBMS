<?php
// Include database configuration
include '../config/config.php';

// Set headers for JSON response
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Origin: *");

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Get request data
$data = json_decode(file_get_contents("php://input"), true);

// Switch based on HTTP method
switch ($method) {
    case 'GET':
        if (isset($_GET['beach_id'])) {
            // Fetch a single beach by ID
            $stmt = $db->prepare("SELECT * FROM beaches WHERE beach_id = ?");
            $stmt->execute([$_GET['beach_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Fetch all beaches
            $stmt = $db->query("SELECT * FROM beaches");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($result);
        break;

    case 'POST':
        if (!empty($data['beach_name']) && !empty($data['user_id'])) {
            // Insert a new beach
            $stmt = $db->prepare("
                INSERT INTO beaches (beach_name, description, location, latitude, longitude, user_id, gcash_qr_code, gcash_name, gcash_phone_number, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $success = $stmt->execute([
                $data['beach_name'],
                $data['description'] ?? null,
                $data['location'] ?? null,
                $data['latitude'] ?? null,
                $data['longitude'] ?? null,
                $data['user_id'],
                $data['gcash_qr_code'] ?? null,
                $data['gcash_name'] ?? null,
                $data['gcash_phone_number'] ?? null,
                $data['image'] ?? null
            ]);
            echo json_encode(["success" => $success]);
        } else {
            echo json_encode(["error" => "Missing required fields"]);
        }
        break;

    case 'PUT':
        if (!empty($data['beach_id']) && !empty($data['beach_name'])) {
            // Update an existing beach
            $stmt = $db->prepare("
                UPDATE beaches
                SET beach_name = ?, description = ?, location = ?, latitude = ?, longitude = ?, gcash_qr_code = ?, gcash_name = ?, gcash_phone_number = ?, image = ?
                WHERE beach_id = ?
            ");
            $success = $stmt->execute([
                $data['beach_name'],
                $data['description'] ?? null,
                $data['location'] ?? null,
                $data['latitude'] ?? null,
                $data['longitude'] ?? null,
                $data['gcash_qr_code'] ?? null,
                $data['gcash_name'] ?? null,
                $data['gcash_phone_number'] ?? null,
                $data['image'] ?? null,
                $data['beach_id']
            ]);
            echo json_encode(["success" => $success]);
        } else {
            echo json_encode(["error" => "Missing required fields"]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['beach_id'])) {
            // Delete a beach
            $stmt = $db->prepare("DELETE FROM beaches WHERE beach_id = ?");
            $success = $stmt->execute([$_GET['beach_id']]);
            echo json_encode(["success" => $success]);
        } else {
            echo json_encode(["error" => "Missing beach_id"]);
        }
        break;

    default:
        // Invalid method
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Method not allowed"]);
        break;
}
?>
