<?php
// Database dbection using PDO
include '../config/config.php';
include 'checkuser.php';



$query = "SELECT * FROM beaches WHERE beach_id = :beach_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);
$stmt->execute();
$beach = $stmt->fetch(PDO::FETCH_ASSOC);



// Fetch amenities data
$amenity_sql = "SELECT * FROM amenities";
$amenity_result = $db->query($amenity_sql);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $beach_id = intval($_GET['id']);

    try {
        // Fetch beach details
        $query = "SELECT * FROM beaches WHERE beach_id = :beach_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);
        $stmt->execute();
        $beach = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch amenities
        $amenityQuery = "SELECT * FROM amenities WHERE beach_id = :beach_id";
        $amenitiesStmt = $db->prepare($amenityQuery);
        $amenitiesStmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);
        $amenitiesStmt->execute();
        $amenities = $amenitiesStmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<p class='text-center'>Invalid beach ID.</p>";
    exit();
}

// Initialize search and status variables
$search = isset($_POST['search']) ? $_POST['search'] : '';
$status_filter = isset($_POST['status_filter']) ? $_POST['status_filter'] : '';
$amenity_search = isset($_POST['amenity_search']) ? $_POST['amenity_search'] : ''; // Amenity search variable



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



<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - BRBMS</title>
    <meta name="description" content="Beach Resort Bazaar Management System">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/Nunito.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/Articles-Cards-images.css">
    <link rel="stylesheet" href="../assets/css/Navbar-Right-Links-icons.css">
    <link rel="stylesheet" href="../assets/css/Pricing-Clean-badges.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
</head>

<body id="page-top">
    <div id="wrapper">
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-expand bg-white shadow mb-0 topbar">
                    <div class="container-fluid"><a class="navbar-brand d-flex align-items-center" href="#"><span>BRBMS</span></a>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search amenities..."><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                        </form>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link active" href="index.php"><br><span style="color: rgb(62, 74, 89); background-color: initial;">Home</span><br><br></a></li>
                            <li class="nav-item"><a class="nav-link" href="index-1.html"><br><span style="color: rgb(62, 74, 89); background-color: initial;">Beaches</span><br><br><br></a></li>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><i class="fas fa-search"></i></a>
                                <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in" aria-labelledby="searchDropdown">
                                    <form class="me-auto navbar-search w-100">
                                        <div class="input-group"><input class="bg-light border-0 form-control small" type="text" placeholder="Search for ..."><button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button></div>
                                    </form>
                                </div>
                            </li>
                           
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small">Valerie Luna</span><img class="border rounded-circle img-profile" src="../assets/img/avatars/avatar1.jpeg"></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a><a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Settings</a><a class="dropdown-item" href="#"><i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Activity log</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container">
                <?php
        try {
            include '../config/config.php';
            // Fetch data from the 'beaches' table
            $sql = "SELECT * FROM beaches WHERE beach_id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $_GET['id']);
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '
                    <picture>
                        <img class="mt-5" src="../admin/php/uploads/' . $row['image'] . '" width="100%" height="600px";
                    </picture>
                    ';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    <h3 class="text-dark mb-4 mt-5">Profile</h3>
    <div class="row">
        <!-- Description and Location Section (Static) -->
        <!-- Can be left as is or replaced with dynamic data -->
    </div>
    <?php
    include '../customer/amenities/check_availability.php';
    ?>


    <h3 class="text-center text-dark mb-2 mt-5"><strong>Our Amenities</strong></h3>
    <div class="row gy-4 row-cols-1 row-cols-md-2 row-cols-xl-3">
        <?php
        // Sample Database Query to Fetch Beach Data
        $result = $db->query("SELECT * FROM amenities");
        while($row = $result->fetch()) {
            echo '
             <div class="col">
                <div class="card border-primary border-2">
                    <img class="card-img-top w-100 d-block fit-cover" style="height: 200px;" src="../admin/uploads/amenity_images/' . $row['image'] . '">
                    <div class="card-body p-4">
                        <span class="badge bg-primary position-absolute top-0 end-0 rounded-bottom-left text-uppercase">Most Popular</span>
                        <p class="text-primary card-text mb-0">' . $row['amenity_type'] . '</p>
                        <h4 class="card-title">' . $row['name'] . '</h4>
                        <p class="card-text">' . $row['description'] . '</p>
                        <a class="btn btn-primary d-block w-100" role="button" href="#">Reserve</a>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>

    <h3 class="text-center text-dark mb-4 mt-5"><strong>We Are Located At</strong></h3>
    <section class="position-relative py-4 py-xl-5">
        <div class="container position-relative">
            <div class="row">
                <div class="col">
                
              <!-- DIRI IBUTANG IMUHA MAPA  -->
              <div id="map" style="width: 100%; height: 500px;"></div>

              <!-- DIRI IBUTANG IMUHA MAPA  -->

                </div>
                <div class="col-md-6 col-xl-4">
                    <form class="p-3 p-xl-4" method="post">
                        <h4>Contact us</h4>
                        <p class="text-muted">Eros ligula lobortis elementum amet commodo ac nibh ornare, eu lobortis.</p>
                        <div class="mb-3"><label class="form-label" for="name">Name</label><input class="form-control" type="text" id="name" name="name"></div>
                        <div class="mb-3"><label class="form-label" for="email">Email</label><input class="form-control" type="email" id="email" name="email"></div>
                        <div class="mb-3"><label class="form-label" for="message">Message</label><textarea class="form-control" id="message" name="message" rows="6"></textarea></div>
                        <div class="mb-3"><button class="btn btn-primary" type="submit">Send</button></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © BRBMS 2024</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/theme.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script>
    // Initialize the map and set the default view
    let map = L.map('map').setView([<?= htmlspecialchars($beach['latitude']) ?>, <?= htmlspecialchars($beach['longitude']) ?>], 13);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add a marker with a popup for the beach location
    let beachMarker = L.marker([<?= htmlspecialchars($beach['latitude']) ?>, <?= htmlspecialchars($beach['longitude']) ?>])
        .addTo(map)
        .bindPopup('<b><?= htmlspecialchars($beach['beach_name']) ?></b><br><?= htmlspecialchars($beach['location']) ?>')
        .openPopup();

    // User location marker and routing control
    let userMarker;
    let routingControl;

    // Function to track user's current location
    function trackUser() {
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;

                    // Add or update the user's location marker
                    if (!userMarker) {
                        userMarker = L.marker([latitude, longitude])
                            .addTo(map)
                            .bindPopup("You are here!");
                    } else {
                        userMarker.setLatLng([latitude, longitude]);
                    }

                    // Adjust the map view to fit both the user and destination markers
                    let bounds = L.latLngBounds([
                        [latitude, longitude], // User's location
                        [<?= htmlspecialchars($beach['latitude']) ?>, <?= htmlspecialchars($beach['longitude']) ?>] // Beach location
                    ]);
                    map.fitBounds(bounds);

                    // Add routing from the user's location to the beach
                    if (routingControl) {
                        routingControl.remove();
                    }
                    routingControl = L.Routing.control({
                        waypoints: [
                            L.latLng(latitude, longitude), // User's location
                            L.latLng(<?= htmlspecialchars($beach['latitude']) ?>, <?= htmlspecialchars($beach['longitude']) ?>) // Beach location
                        ],
                        routeWhileDragging: true,
                        lineOptions: {
                            styles: [{ color: 'red', weight: 5 }]

                        }
                    }).addTo(map);
                },
                (error) => console.error(error),
                { enableHighAccuracy: true }
            );
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    }

    // Call the function to track the user
    trackUser();
</script>

</body>

</html>