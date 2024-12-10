<?php
require_once('../../config.php');
session_start();  // Start the session

// Ensure required fields are posted
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['beach_id'])) {
    $_SESSION['error'] = 'Invalid request!';
    header('Location: /myproject/admin/manage_beaches.php');
    exit;
}

// Collect data from the form
$beach_id = htmlspecialchars(trim($_POST['beach_id']));
$gcash_name = isset($_POST['gcash_name']) ? htmlspecialchars(trim($_POST['gcash_name'])) : '';
$gcash_phone_number = isset($_POST['gcash_phone_number']) ? htmlspecialchars(trim($_POST['gcash_phone_number'])) : '';
$existing_gcash_name = $_POST['existing_gcash_name'] ?? '';  // Existing GCash Name
$existing_gcash_phone = $_POST['existing_gcash_phone_number'] ?? '';  // Existing GCash Phone

// Handle GCash Name Update
$gcash_name = !empty($gcash_name) ? $gcash_name : $existing_gcash_name;

// Handle GCash Phone Number Update
$gcash_phone_number = !empty($gcash_phone_number) ? $gcash_phone_number : $existing_gcash_phone;

// Handle GCash QR Code Upload (if any)
$gcash_qr_code = $_FILES['gcash_qr_code'] ?? null;  // QR Code input
$gcash_qr_code_name = $existing_qr_code = $_POST['existing_qr_code'] ?? ''; // Existing QR Code

// Function to handle file uploads
function handleFileUpload($file, $existingFileName, $allowedTypes, $uploadDir) {
    if ($file && $file['error'] === 0) {
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type for ' . htmlspecialchars($file['name']));
        }

        $newFileName = time() . "_" . basename($file['name']);
        $targetPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Failed to upload ' . htmlspecialchars($file['name']));
        }

        return $newFileName; // Return new file name if successful
    }

    return $existingFileName; // Return existing file name if no new file uploaded
}

try {
    // Directory for uploads
    $uploadDir = "../uploads/";
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    // Handle QR code upload if new QR code is uploaded
    $gcash_qr_code_name = handleFileUpload($gcash_qr_code, $gcash_qr_code_name, $allowedTypes, $uploadDir);

    // Prepare SQL query to update the GCash information in the database
    $stmt = $db->prepare("UPDATE beaches SET 
        gcash_name = :gcash_name, 
        gcash_phone_number = :gcash_phone_number, 
        gcash_qr_code = :gcash_qr_code 
        WHERE beach_id = :beach_id");

    // Execute query with parameters
    $stmt->execute([
        'gcash_name' => $gcash_name,
        'gcash_phone_number' => $gcash_phone_number,
        'gcash_qr_code' => $gcash_qr_code_name,
        'beach_id' => $beach_id
    ]);

    // Redirect with success message
    $_SESSION['success'] = 'GCash information updated successfully!';
    header('Location: /myproject/admin/manage_beaches.php');
    exit;

} catch (Exception $e) {
    // Log the error for debugging
    error_log($e->getMessage());

    // Redirect with error message
    $_SESSION['error'] = 'Error updating GCash information: ' . $e->getMessage();
    header('Location: /myproject/admin/manage_beaches.php');
    exit;
}
// Debugging to check if data is being received
error_log("GCash Name: " . $gcash_name);
error_log("GCash Phone: " . $gcash_phone_number);
error_log("GCash QR Code: " . $gcash_qr_code_name);

?>
