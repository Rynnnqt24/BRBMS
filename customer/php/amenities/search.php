<?php
session_start();
include('../../config/config.php');

if (!isset($_GET['action'])) {
    echo "Invalid request.";
    exit;
}

$action = htmlspecialchars($_GET['action']);

try {
    switch ($action) {
        case 'search_amenities': // Search available amenities
            if (isset($_GET['search'])) {
                $search = htmlspecialchars($_GET['search']);
                $stmt = $db->prepare("
                    SELECT * 
                    FROM amenities 
                    WHERE status = 'available' 
                      AND amenity_name LIKE :search
                ");
                $likeSearch = '%' . $search . '%';
                $stmt->bindParam(':search', $likeSearch, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div>" . htmlspecialchars($row['amenity_name']) . " - " . htmlspecialchars($row['status']) . "</div>";
                    }
                } else {
                    echo "<div>No available amenities found.</div>";
                }
            }
            break;

        case 'search_beaches': // Search beaches
            if (isset($_GET['search'])) {
                $search = htmlspecialchars($_GET['search']);
                $stmt = $db->prepare("
                    SELECT * 
                    FROM beaches 
                    WHERE beach_name LIKE :search
                ");
                $likeSearch = '%' . $search . '%';
                $stmt->bindParam(':search', $likeSearch, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div>" . htmlspecialchars($row['beach_name']) . " - " . htmlspecialchars($row['location']) . "</div>";
                    }
                } else {
                    echo "<div>No beaches found.</div>";
                }
            }
            break;

        case 'all_amenities': // Get all amenities
            $stmt = $db->prepare("SELECT * FROM amenities");
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div>" . htmlspecialchars($row['amenity_name']) . " - " . htmlspecialchars($row['status']) . "</div>";
                }
            } else {
                echo "<div>No amenities found.</div>";
            }
            break;

        case 'amenities_by_beach': // Get amenities by beach
            if (isset($_GET['beach_id'])) {
                $beach_id = htmlspecialchars($_GET['beach_id']);
                $stmt = $db->prepare("
                    SELECT * 
                    FROM amenities 
                    WHERE beach_id = :beach_id
                ");
                $stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div>" . htmlspecialchars($row['amenity_name']) . " - " . htmlspecialchars($row['status']) . "</div>";
                    }
                } else {
                    echo "<div>No amenities found for this beach.</div>";
                }
            }
            break;

        case 'check_availability': // Check availability of amenities
            if (isset($_GET['amenity_id']) && isset($_GET['checkin_date'])) {
                $amenity_id = htmlspecialchars($_GET['amenity_id']);
                $checkin_date = htmlspecialchars($_GET['checkin_date']);

                $stmt = $db->prepare("
                    SELECT * 
                    FROM reservations 
                    WHERE amenity_id = :amenity_id 
                      AND reservation_date = :checkin_date
                ");
                $stmt->bindParam(':amenity_id', $amenity_id, PDO::PARAM_INT);
                $stmt->bindParam(':checkin_date', $checkin_date, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() === 0) {
                    echo "<div>Amenity is available for the selected date.</div>";
                } else {
                    echo "<div>Amenity is already reserved for this date.</div>";
                }
            }
            break;

        default:
            echo "Invalid action.";
            break;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
