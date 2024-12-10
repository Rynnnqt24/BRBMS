<?php
require_once('../../config/config.php');

include '../checkuser.php';

// Assuming the owner_id is stored in the session and is used to filter reservations
$owner_id = $_SESSION['user_id']; 

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

    // SQL query to fetch the reservations for the logged-in owner using beach_id
    $query = "SELECT r.reservation_id, r.customer_name, r.checkin_date, r.checkout_date, r.status, r.payment_status, a.name 
              FROM reservations r
              JOIN amenities a ON r.amenity_id = a.amenity_id 
              WHERE r.beach_id = :beach_id ORDER BY r.reservation_date DESC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch the reservations data
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Capture any database error and display it
    echo "Error: " . $e->getMessage();
}
?>
