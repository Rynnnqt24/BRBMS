<?php
// Fetch reservation details by reservation_id
$reservation_id = $_GET['reservation_id'];
$query = "
    SELECT r.*, a.name AS amenity_name, b.name AS beach_name
    FROM reservations r
    JOIN amenities a ON r.amenity_id = a.amenity_id
    JOIN beaches b ON r.beach_id = b.beach_id
    WHERE r.reservation_id = :reservation_id
";
$stmt = $pdo->prepare($query);
$stmt->execute(['reservation_id' => $reservation_id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h1>Reservation Details</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Reservation ID:</strong> <?= htmlspecialchars($reservation['reservation_id']); ?></p>
            <p><strong>Customer Name:</strong> <?= htmlspecialchars($reservation['customer_name']); ?></p>
            <p><strong>Amenity:</strong> <?= htmlspecialchars($reservation['amenity_name']); ?></p>
            <p><strong>Beach:</strong> <?= htmlspecialchars($reservation['beach_name']); ?></p>
            <p><strong>Check-in Date:</strong> <?= htmlspecialchars($reservation['checkin_date']); ?></p>
            <p><strong>Check-out Date:</strong> <?= htmlspecialchars($reservation['checkout_date']); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($reservation['status']); ?></p>
            <p><strong>Payment Status:</strong> <?= htmlspecialchars($reservation['payment_status']); ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($reservation['payment_method']); ?></p>
            <p><strong>Amount Paid:</strong> <?= htmlspecialchars($reservation['total_amount']); ?></p>
            <a href="manage_reservations.php" class="btn btn-secondary">Back to Reservations</a>
        </div>
    </div>
</div>
