<?php
// Include database connection
include 'checkuser.php'; 

// Get the user's beaches
$user_id = $_SESSION['user_id']; // Ensure the user is logged in and their ID is stored in the session
$query = "SELECT beach_id, beach_name FROM beaches WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>