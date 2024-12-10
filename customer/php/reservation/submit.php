<?php
// Include database connection
include '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data and sanitize inputs
    $user_id = $_POST['user_id']; // Assuming the user is logged in and their ID is available
    $beach_id = $_POST['beach_id'];
    $amenity_id = $_POST['amenity_id'];
    $quantity = (int) $_POST['quantity'];
    $total_amount = (float) $_POST['total_amount'];
    $status = 'pending'; // Default status
    $payment_status = $_POST['payment_status'];
    $payment_method = $_POST['payment_method'];
    $customer_name = htmlspecialchars(trim($_POST['customer_name']));
    $customer_address = htmlspecialchars(trim($_POST['customer_address']));
    $contact_number = htmlspecialchars(trim($_POST['contact_number']));
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $reference_number = htmlspecialchars(trim($_POST['reference_number']));
    $reservation_date = date('Y-m-d H:i:s'); // Current date and time

    // Basic validation
    if (empty($user_id) || empty($beach_id) || empty($amenity_id) || empty($quantity) || empty($total_amount) || empty($payment_status) || empty($payment_method) || empty($customer_name) || empty($customer_address) || empty($contact_number) || empty($checkin_date) || empty($checkout_date) || empty($reference_number)) {
        die("All fields are required.");
    }

    // Prepare SQL query to insert reservation into database
    $query = "INSERT INTO reservations (user_id, beach_id, amenity_id, reservation_date, quantity, total_amount, status, payment_status, payment_method, customer_name, customer_address, contact_number, checkin_date, checkout_date, reference_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(1, $user_id);
    $stmt->bindParam(2, $beach_id);
    $stmt->bindParam(3, $amenity_id);
    $stmt->bindParam(4, $reservation_date);
    $stmt->bindParam(5, $quantity);
    $stmt->bindParam(6, $total_amount);
    $stmt->bindParam(7, $status);
    $stmt->bindParam(8, $payment_status);
    $stmt->bindParam(9, $payment_method);
    $stmt->bindParam(10, $customer_name);
    $stmt->bindParam(11, $customer_address);
    $stmt->bindParam(12, $contact_number);
    $stmt->bindParam(13, $checkin_date);
    $stmt->bindParam(14, $checkout_date);
    $stmt->bindParam(15, $reference_number);

    if ($stmt->execute()) {
        echo "Reservation successfully made!";
    } else {
        echo "Error in making reservation.";
    }
}
?>
