<?php
require_once('../../config/config.php');
include '../checkuser.php';

// Get the reservation ID from the URL
if (isset($_GET['reservation_id'])) {
    $reservation_id = intval($_GET['reservation_id']);
    
    try {
        // Query to fetch the reservation details
        $query = "SELECT r.*, a.name AS amenity_name 
                  FROM reservations r 
                  JOIN amenities a ON r.amenity_id = a.amenity_id 
                  WHERE r.reservation_id = :reservation_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the reservation details
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
            echo "Reservation not found.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "No reservation ID provided.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="manage.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Reservations
            </a>
        </div>

        <!-- Reservation Details Card -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Reservation Details</h3>
            </div>
            <div class="card-body">
                <p><strong>Reservation ID:</strong> <?= htmlspecialchars($reservation['reservation_id']); ?></p>
                <p><strong>Customer Name:</strong> <?= htmlspecialchars($reservation['customer_name']); ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($reservation['customer_address']); ?></p>
                <p><strong>Contact Number:</strong> <?= htmlspecialchars($reservation['contact_number']); ?></p>
                <p><strong>Reservation Date:</strong> <?= htmlspecialchars($reservation['reservation_date']); ?></p>
                <p><strong>Quantity:</strong> <?= htmlspecialchars($reservation['quantity']); ?></p>
                <p><strong>Total Amount:</strong> <?= htmlspecialchars(number_format($reservation['total_amount'], 2)); ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars(ucwords($reservation['status'])); ?></p>
                <p><strong>Payment Status:</strong> <?= htmlspecialchars(ucwords($reservation['payment_status'])); ?></p>
                <p><strong>Payment Method:</strong> <?= htmlspecialchars($reservation['payment_method']); ?></p>
                <p><strong>Check-in Date:</strong> <?= htmlspecialchars($reservation['checkin_date']); ?></p>
                <p><strong>Check-out Date:</strong> <?= htmlspecialchars($reservation['checkout_date']); ?></p>
                <p><strong>Reference Number:</strong> <?= htmlspecialchars($reservation['reference_number']); ?></p>
                <p><strong>Amenity:</strong> <?= htmlspecialchars($reservation['amenity_name']); ?></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>

