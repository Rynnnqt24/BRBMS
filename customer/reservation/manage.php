<?php
// Assuming the session is started and the customer is logged in
session_start();

// Assuming you have the user's ID stored in the session
$user_id = $_SESSION['user_id'];  // Logged-in customer ID

// Database connection (make sure to replace with your actual connection details)
$pdo = new PDO("mysql:host=localhost;dbname=bazaar_management_system", "root", "");

// SQL query to fetch reservations specific to the logged-in customer
$query = "
    SELECT r.*, a.name AS amenity_name
    FROM reservations r
    JOIN amenities a ON r.amenity_id = a.amenity_id
    WHERE r.user_id = :user_id
";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Frontend: HTML Table to display customer reservations -->
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Reservation ID</th>
            <th>Amenity</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Payment Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reservations as $reservation): ?>
            <tr onclick="location.href='view_reservation.php?reservation_id=<?= $reservation['reservation_id']; ?>'" style="cursor: pointer;">
                <td><?= htmlspecialchars($reservation['reservation_id']); ?></td>
                <td><?= ucwords(htmlspecialchars($reservation['amenity_name'])); ?></td>
                <td><?= htmlspecialchars($reservation['checkin_date']); ?></td>
                <td><?= htmlspecialchars($reservation['checkout_date']); ?></td>
                <td><?= htmlspecialchars($reservation['status']); ?></td>
                <td><?= htmlspecialchars($reservation['payment_status']); ?></td>
                <td>
                    <!-- Actions for customers: e.g., Cancel Reservation -->
                    <form method="POST" action="cancel_reservation.php">
                        <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id']; ?>">
                        <button type="submit" class="btn btn-danger">Cancel Reservation</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
