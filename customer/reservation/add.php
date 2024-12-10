<?php
require_once('../../config/config.php');
include '../checkuser.php';

// Assuming the owner_id is stored in the session and used to filter reservations
$owner_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the form
    $customer_name = $_POST['customer_name'];
    $amenity_id = $_POST['amenity_id'];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $quantity = $_POST['quantity'];
    $total_amount = $_POST['total_amount'];

    try {
        // Get the beach_id for the logged-in owner
        $beachQuery = "SELECT beach_id FROM beaches WHERE user_id = :owner_id";
        $beachStmt = $db->prepare($beachQuery);
        $beachStmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);
        $beachStmt->execute();
        
        $beach = $beachStmt->fetch(PDO::FETCH_ASSOC);
        if (!$beach) {
            echo "No beach found for this owner.";
            exit();
        }
        $beach_id = $beach['beach_id'];

        // Prepare the insert query
        $query = "INSERT INTO reservations (user_id, beach_id, amenity_id, reservation_date, quantity, total_amount, status, payment_status, payment_method, customer_name, checkin_date, checkout_date, reference_number) 
                  VALUES (:user_id, :beach_id, :amenity_id, NOW(), :quantity, :total_amount, 'pending', 'pending', 'GCash', :customer_name, :checkin_date, :checkout_date, :reference_number)";
        
        // Prepare the statement
        $stmt = $db->prepare($query);

        // Generate a unique reference number for the reservation
        $reference_number = uniqid('RES-', true);

        // Bind parameters
        $stmt->bindParam(':user_id', $owner_id, PDO::PARAM_INT);
        $stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);
        $stmt->bindParam(':amenity_id', $amenity_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
        $stmt->bindParam(':customer_name', $customer_name, PDO::PARAM_STR);
        $stmt->bindParam(':checkin_date', $checkin_date, PDO::PARAM_STR);
        $stmt->bindParam(':checkout_date', $checkout_date, PDO::PARAM_STR);
        $stmt->bindParam(':reference_number', $reference_number, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        echo "Reservation created successfully!";
    } catch (PDOException $e) {
        // Capture any database error and display it
        echo "Error: " . $e->getMessage();
    }
}
?>
