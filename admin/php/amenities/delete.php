<?php
session_start();
require_once('../../../config/config.php'); // Include your database connection file

if (isset($_GET['id'])) {
    $amenity_id = $_GET['id']; // Get the amenity_id from the URL query

    if (empty($amenity_id)) {
        $_SESSION['error'] = "Invalid Amenity ID.";
        header("Location: /BRBMS/admin/amenities/manage.php");
        exit();
    }

    try {
        // Prepare DELETE SQL query
        $stmt = $db->prepare("DELETE FROM amenities WHERE amenity_id = :amenity_id");

        // Execute query with the amenity_id
        $stmt->execute(['amenity_id' => $amenity_id]);

        // Set success message
        $_SESSION['success'] = "Amenity deleted successfully!";
        header("Location: /BRBMS/admin/amenities/manage.php");
    } catch (PDOException $e) {
        // Set error message in case of a failure
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: /BRBMS/admin/amenities/manage.php");
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: /BRBMS/admin/amenities/manage.php");
}
?>
