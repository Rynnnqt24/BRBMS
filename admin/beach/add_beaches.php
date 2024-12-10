<?php
session_start();
    require_once('../../config.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_beach'])) {
    // Ensure the user is logged in as an owner
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'owner') {
        header('Location: login.php');
        exit();
    }

    $owner_id = $_SESSION['user_id'];
    $beach_name = trim($_POST['beach_name']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $gcash_qr_code = null;
    $image_path = null;

    // Validate Latitude and Longitude
    if (!is_numeric($latitude) || !is_numeric($longitude)) {
        $_SESSION['error'] = 'Invalid latitude or longitude.';
        header('Location: /myproject/admin/manage_beaches.php');
        exit();
    }

    // Validate and upload GCASH QR Code (optional)
    if (!empty($_FILES['gcash_qr_code']['name'])) {
        $qr_code_file = $_FILES['gcash_qr_code'];
        $qr_code_ext = strtolower(pathinfo($qr_code_file['name'], PATHINFO_EXTENSION));

        $allowed_qr_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($qr_code_ext, $allowed_qr_extensions)) {
            $_SESSION['error'] = 'GCash QR Code must be an image (JPG, JPEG, PNG, GIF).';
            header('Location: /myproject/admin/manage_beaches.php');
            exit();
        }

        $qr_code_new_name = uniqid() . '.' . $qr_code_ext;
        $qr_code_path = '../uploads/gcash_qr_codes/' . $qr_code_new_name;

        if (!is_dir('../uploads/gcash_qr_codes')) {
            mkdir('../uploads/gcash_qr_codes', 0777, true);
        }

        if (move_uploaded_file($qr_code_file['tmp_name'], $qr_code_path)) {
            $gcash_qr_code = $qr_code_path;
        } else {
            $_SESSION['error'] = 'Failed to upload GCash QR Code.';
            header('Location: /myproject/admin/manage_beaches.php');
            exit();
        }
    }

    // Validate and upload main beach image (one image required)
    if (!empty($_FILES['image']['name'])) {
        $image_file = $_FILES['image'];
        $image_ext = strtolower(pathinfo($image_file['name'], PATHINFO_EXTENSION));

        $allowed_image_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($image_ext, $allowed_image_extensions)) {
            $_SESSION['error'] = 'Main image must be a valid image (JPG, JPEG, PNG, GIF).';
            header('Location: /myproject/admin/manage_beaches.php');
            exit();
        }

        $image_new_name = uniqid() . '.' . $image_ext;
        $image_path = '../uploads/beach_images/' . $image_new_name;

        if (!is_dir('../uploads/beach_images')) {
            mkdir('../uploads/beach_images', 0777, true);
        }

        if (!move_uploaded_file($image_file['tmp_name'], $image_path)) {
            $_SESSION['error'] = 'Failed to upload main image.';
            header('Location: /myproject/admin/manage_beaches.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Main beach image is required.';
        header('Location: /myproject/admin/manage_beaches.php');
        exit();
    }

    try {
        // Insert beach details into the database
        $query = "INSERT INTO beaches (
                      beach_name, description, location, latitude, longitude, user_id, gcash_qr_code, image
                  ) VALUES (
                      :beach_name, :description, :location, :latitude, :longitude, :user_id, :gcash_qr_code, :image
                  )";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':beach_name', $beach_name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':latitude', $latitude, PDO::PARAM_STR);
        $stmt->bindParam(':longitude', $longitude, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $owner_id, PDO::PARAM_INT);
        $stmt->bindParam(':gcash_qr_code', $gcash_qr_code, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image_path, PDO::PARAM_STR);

        $stmt->execute();

        $_SESSION['success'] = 'Beach added successfully!';
        header('Location: /myproject/admin/manage_beaches.php');
        exit();
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage(), 3, 'errors.log');
        $_SESSION['error'] = 'An error occurred. Please try again later.';
        header('Location: /myproject/admin/manage_beaches.php');
        exit();
    }
} else {
    header('Location: /myproject/admin/manage_beaches.php');
    exit();
}
?>
