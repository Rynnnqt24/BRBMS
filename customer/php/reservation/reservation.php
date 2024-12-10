<?php
// Connect to the database
$pdo = new PDO("mysql:host=localhost;dbname=bazaar_management_system", "root", "");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form
    $customer_name = $_POST['customer_name'];
    $customer_address = $_POST['customer_address'];
    $contact_number = $_POST['contact_number'];
    $amenity_id = $_POST['amenity_id'];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $payment_method = $_POST['payment_method'];
    $total_amount = $_POST['total_amount'];

    // Assuming the user is logged in and user_id is stored in the session
    session_start();
    $user_id = $_SESSION['user_id'];

    // Insert reservation into the database
    $query = "
        INSERT INTO reservations (user_id, amenity_id, reservation_date, checkin_date, checkout_date, payment_method, total_amount, customer_name, customer_address, contact_number, status, payment_status)
        VALUES (:user_id, :amenity_id, NOW(), :checkin_date, :checkout_date, :payment_method, :total_amount, :customer_name, :customer_address, :contact_number, 'pending', 'pending')
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'user_id' => $user_id,
        'amenity_id' => $amenity_id,
        'checkin_date' => $checkin_date,
        'checkout_date' => $checkout_date,
        'payment_method' => $payment_method,
        'total_amount' => $total_amount,
        'customer_name' => $customer_name,
        'customer_address' => $customer_address,
        'contact_number' => $contact_number,
    ]);

    // Redirect to a success page or show a message
    header("Location: reservation_success.php");
    exit();
}
?>
