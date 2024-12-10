<?php
// Include database connection and session management
require_once('../../../config/config.php');
include '../../checkuser.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Capture the form data
        $reservation_id = $_POST['reservation_id'] ?? null;
        $status = $_POST['status'] ?? null;
        

        // Ensure required fields are provided
        if (!$reservation_id || !$status) {
            die("Missing reservation ID or status.");
        }

        // Prepare the SQL query to update the reservation
        $query = "UPDATE reservations 
                  SET status = :status
                  WHERE reservation_id = :reservation_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect back to the reservation management page with success
            header("Location: /BRBMS/admin/reservation/manage.php?success=Reservation updated successfully.");
            exit();
        } else {
            // Handle failure
            die("Failed to update the reservation. Please try again.");
        }
    } catch (PDOException $e) {
        // Handle database errors
        die("Error: " . $e->getMessage());
    }
} else {
    // Redirect if accessed without POST
    header("Location: admin/reservation/manage.php");
    exit();
}
