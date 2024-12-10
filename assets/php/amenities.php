<?php
// Include database connection
include '../config/config.php';

// Set the response header to JSON format
header('Content-Type: application/json');

// Parse the incoming request method
$method = $_SERVER['REQUEST_METHOD'];

// Function to parse raw input (used for PUT/DELETE)
function parseRawInput()
{
    parse_str(file_get_contents("php://input"), $data);
    return $data;
}

// Handle different request methods
switch ($method) {
    case 'POST': // Create
        if (isset($_POST['name'], $_POST['description'], $_POST['type'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $type = $_POST['type'];

            $query = "INSERT INTO amenities (name, description, amenity_type) VALUES (:name, :description, :type)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Amenity created successfully.']);
            } else {
                echo json_encode(['error' => 'Failed to create amenity.']);
            }
        } else {
            echo json_encode(['error' => 'Missing parameters.']);
        }
        break;

    case 'GET': // Read
        if (isset($_GET['id'])) {
            // Fetch a specific record
            $id = intval($_GET['id']);
            $query = "SELECT * FROM amenities WHERE amenity_id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Amenity not found.']);
            }
        } else {
            // Fetch all records
            $query = "SELECT * FROM amenities";
            $stmt = $db->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        }
        break;

    case 'PUT': // Update
        $data = parseRawInput();
        if (isset($data['id'], $data['name'], $data['description'], $data['type'])) {
            $id = intval($data['id']);
            $name = $data['name'];
            $description = $data['description'];
            $type = $data['type'];

            $query = "UPDATE amenities SET name = :name, description = :description, amenity_type = :type WHERE amenity_id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Amenity updated successfully.']);
            } else {
                echo json_encode(['error' => 'Failed to update amenity.']);
            }
        } else {
            echo json_encode(['error' => 'Missing parameters.']);
        }
        break;

    case 'DELETE': // Delete
        $data = parseRawInput();
        if (isset($data['id'])) {
            $id = intval($data['id']);
            $query = "DELETE FROM amenities WHERE amenity_id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Amenity deleted successfully.']);
            } else {
                echo json_encode(['error' => 'Failed to delete amenity.']);
            }
        } else {
            echo json_encode(['error' => 'Missing parameters.']);
        }
        break;

    default:
        // Handle unsupported request methods
        echo json_encode(['error' => 'Invalid request method.']);
        break;
}
?>
