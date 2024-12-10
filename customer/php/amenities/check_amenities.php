<?php
// Assuming you're already connected to your database using PDO
include '../../../config/config.php';


        // Fetch beach details
        $query = "SELECT * FROM beaches WHERE beach_id = :beach_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);
        $stmt->execute();
        $beach = $stmt->fetch(PDO::FETCH_ASSOC);
        
if (isset($_GET['beach_id']) && is_numeric($_GET['beach_id'])) {
    $beach_id = intval($_GET['id']);
} else {
    echo "<p class='text-center'>Invalid beach ID.</p>";
    exit();
}


// Initialize search and status variables
$search = isset($_POST['search']) ? $_POST['search'] : '';
$status_filter = isset($_POST['status_filter']) ? $_POST['status_filter'] : '';
$amenity_search = isset($_POST['amenity_search']) ? $_POST['amenity_search'] : ''; // Amenity search variable

$owner_id = $_SESSION['user_id']; 

$query = "
    SELECT 
        r.reservation_id,
        r.customer_name,
        r.checkin_date,
        r.checkout_date,
        r.status,
        r.payment_status,
        a.name as amenity_name
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

// Add amenity search filter
if (!empty($amenity_search)) {
    $query .= " AND a.name LIKE :amenity_search";
}

if (!empty($status_filter)) {
    $query .= " AND r.status = :status_filter";
}

$query .= " ORDER BY r.reservation_date DESC";

// Prepare and bind parameters
$stmt = $db->prepare($query);
$stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);

if (!empty($search)) {
    $search_term = "%$search%";
    $stmt->bindParam(':search', $search_term, PDO::PARAM_STR);
}

if (!empty($amenity_search)) {
    $amenity_search_term = "%$amenity_search%";
    $stmt->bindParam(':amenity_search', $amenity_search_term, PDO::PARAM_STR);
}

if (!empty($status_filter)) {
    $stmt->bindParam(':status_filter', $status_filter, PDO::PARAM_STR);
}

$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>