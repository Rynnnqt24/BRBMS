
<?php
require_once('../../../config/config.php');
session_start();  // Ensure session is started
if (isset($_POST['update_beach'])){
// Get the posted data
$beach_id = $_POST['beach_id'];
$name = $_POST['beach_name'];
$description = $_POST['description'];
$location = $_POST['location'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$existing_image = $_POST['existing_image'];  // Store the existing image
$existing_qr_code = $_POST['existing_qr_code'];  // Store the existing QR code
$image = $_FILES['image'] ?? null; // Check if a new image is uploaded
$qr_code = $_FILES['gcash_qr_code'] ?? null; // Check if a new QR code is uploaded

// Handle Beach Image Upload (if any)
if ($image && $image['error'] == 0) {
    // Image handling (upload to the server)
    $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($image['type'], $allowed_image_types)) {
        $image_name = time() . "_" . basename($image['name']);
        $image_path = "../uploads/" . $image_name;

        if (!move_uploaded_file($image['tmp_name'], $image_path)) {
            $_SESSION['error'] = 'Failed to upload image';
            header('Location: /BRBMS/admin/beach/manage.php?beach_id=' . $beach_id); // Fix the URL string concatenation

            exit;
        }
    } else {
        $_SESSION['error'] = 'Invalid image type';
        header('Location: /BRBMS/admin/beach/manage.php?beach_id=' . $beach_id); // Fix the URL string concatenation

        exit;
    }
} else {
    // If no new image is uploaded, keep the existing one
    $image_name = $existing_image;
}

// Handle QR Code Upload (if any)
if ($qr_code && $qr_code['error'] == 0) {
    // QR code handling (upload to the server)
    $allowed_qr_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($qr_code['type'], $allowed_qr_types)) {
        $qr_code_name = time() . "_" . basename($qr_code['name']);
        $qr_code_path = "../uploads/" . $qr_code_name;

        if (!move_uploaded_file($qr_code['tmp_name'], $qr_code_path)) {
            $_SESSION['error'] = 'Failed to upload QR code';
            header('Location: /BRBMS/admin/beach/manage.php?beach_id=' . $beach_id); // Fix the URL string concatenation

            exit;
        }
    } else {
        $_SESSION['error'] = 'Invalid QR code type';
        header('Location: /BRBMS/admin/beach/manage.php?beach_id=' . $beach_id); // Fix the URL string concatenation

        exit;
    }
} else {
    // If no new QR code is uploaded, keep the existing one
    $qr_code_name = $existing_qr_code;
}

// Prepare the SQL query to update the beach data
$stmt = $db->prepare("UPDATE beaches SET 
    beach_name = :beach_name, 
    description = :description, 
    location = :location, 
    latitude = :latitude, 
    longitude = :longitude, 
    image = :image, 
    gcash_qr_code = :gcash_qr_code 
    WHERE beach_id = :beach_id");

try {
    $stmt->execute([
        'beach_name' => $name,
        'description' => $description,
        'location' => $location,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'image' => $image_name,
        'gcash_qr_code' => $qr_code_name,
        'beach_id' => $beach_id
    ]);

    // Redirect to the same page with a success message
    $_SESSION['success'] = 'Beach updated successfully!';
    header('Location: /BRBMS/admin/beach/manage.php?beach_id=' . $beach_id); // Fix the URL string concatenation

    exit;

} catch (Exception $e) {
    // Handle any errors and show an error message
    $_SESSION['error'] = 'Error updating beach: ' . $e->getMessage();
    header('Location: /BRBMS/admin/beach/manage.php?beach_id=' . $beach_id); // Fix the URL string concatenation
    exit;
}

}
?>
