<?php

include '../php/reservation/reservation.php';

// Initialize search and status variables
$search = isset($_POST['search']) ? $_POST['search'] : '';
$status_filter = isset($_POST['status_filter']) ? $_POST['status_filter'] : '';

$owner_id = $_SESSION['user_id']; 

$query = "
    SELECT 
        r.reservation_id,
        r.customer_name,
        r.checkin_date,
        r.checkout_date,
        r.status,
        r.payment_status,
        a.name 
    FROM 
        reservations r
    LEFT JOIN 
        amenities a ON r.amenity_id = a.amenity_id
    WHERE 
        r.beach_id = :beach_id
";

// Add search and status filters
if (!empty($search)) {
    $query .= " AND (r.customer_name LIKE :search OR r.reservation_id LIKE :search)";
}
if (!empty($status_filter)) {
    $query .= " AND r.status = :status_filter";
}
$query .= " ORDER BY r.reservation_date DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);

if (!empty($search)) {
    $search_term = "%$search%";
    $stmt->bindParam(':search', $search_term, PDO::PARAM_STR);
}
if (!empty($status_filter)) {
    $stmt->bindParam(':status_filter', $status_filter, PDO::PARAM_STR);
}

$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>





<!-- Add this in the <head> section of your HTML file -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Add Font Awesome CDN in the head -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<div class="container mt-4">
    <form method="POST" action="manage.php" class="mb-4">
        <div class="input-group mb-3">
        <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" name="search" class="form-control" placeholder="Search by name or ID">
            <select name="status_filter" class="form-select ms-2">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
                <option value="completed">Completed</option>
            </select>
            <button type="submit" class="btn btn-primary ms-2">Filter</button>
        </div>
    </form>

    <table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Reservation ID</th>
            <th>Customer</th>
            <th>Amenity</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reservations as $reservation): ?>
            <tr 
                onclick="if(event.target.tagName !== 'SELECT' && event.target.tagName !== 'BUTTON') location.href='../reservation/view_reservation.php?reservation_id=<?= $reservation['reservation_id']; ?>';" 
                style="cursor: pointer;">
                <td><?= htmlspecialchars($reservation['reservation_id']); ?></td>
                <td><?= htmlspecialchars($reservation['customer_name']); ?></td>
                <td><?= ucwords(htmlspecialchars($reservation['name'])); ?></td>
                <td><?= htmlspecialchars($reservation['checkin_date']); ?></td>
                <td><?= htmlspecialchars($reservation['checkout_date']); ?></td>
                <td><?= htmlspecialchars($reservation['status']); ?></td>
                <td><?= htmlspecialchars($reservation['payment_status']); ?></td>
                <td>
                    <form method="POST" action="../php/reservation/update_reservation.php">
                        <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id']; ?>">
                        <div class="d-flex">
                            <select name="status" class="form-select me-2">
                                <option value="confirmed" <?= $reservation['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirm</option>
                                <option value="cancelled" <?= $reservation['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancel</option>
                                <option value="completed" <?= $reservation['status'] === 'completed' ? 'selected' : ''; ?>>Complete</option>
                            </select>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
