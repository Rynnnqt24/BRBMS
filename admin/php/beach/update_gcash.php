<?php
session_start();
require_once('../../../config/config.php');

if (isset($_POST['update_gcash'])) {
    // Get the posted data
    $beach_id = $_POST['beach_id'];
    $gcash_name = $_POST['gcash_name'];
    $gcash_phone_number = $_POST['gcash_phone_number'];
    $upload_dir = '../uploads/gcash_qr/';

    // Check if the directory exists and create it if not
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);  // Create directory with full permissions
    }
    
    // Ensure the file was uploaded without errors
    if ($_FILES['gcash_qr_code']['error'] == UPLOAD_ERR_OK) {
        $file_name = time() . '-' . basename($_FILES['gcash_qr_code']['name']);
        $upload_file = $upload_dir . $file_name;
    
        if (move_uploaded_file($_FILES['gcash_qr_code']['tmp_name'], $upload_file)) {
            echo "File successfully uploaded!";
        } else {
            echo "Failed to move the uploaded file.";
        }
    } else {
        echo "Error uploading file. Error code: " . $_FILES['gcash_qr_code']['error'];
    }    

    // Update the beach data in the database
    $query = "UPDATE beaches SET gcash_name = :gcash_name, gcash_phone_number = :gcash_phone_number";
    if ($upload_file) {
        $query .= ", gcash_qr_code = :gcash_qr_code";
    }
    $query .= " WHERE beach_id = :beach_id";

    // Prepare and execute the query
    $stmt = $db->prepare($query);
    $stmt->bindParam(':gcash_name', $gcash_name);
    $stmt->bindParam(':gcash_phone_number', $gcash_phone_number);
    if ($upload_file) {
        $stmt->bindParam(':gcash_qr_code', $upload_file);
    }
    $stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('GCash Information Updated Successfully!');</script>";
    } else {
        echo "<script>alert('Error updating GCash information.');</script>";
    }

  
}
?>