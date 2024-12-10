<?php
session_start();
require_once('../../../config/config.php'); // Include your database connection file

if (isset($_POST['add_amenity'])) {
    // Retrieve form data
    $amenity_type = trim($_POST['amenity_type']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $capacity = intval($_POST['capacity']);
    $quantity = intval($_POST['quantity']); // Retrieve quantity
    $image = $_FILES['image'];

    // Ensure the beach_id is available in the session
    if (!isset($_SESSION['beach_id'])) {
        $_SESSION['error'] = "Beach ID is not set.";
        header("Location: /BRBMS/admin/amenities/manage.php");
        exit();
    }
    $beach_id = $_SESSION['beach_id'];

    // Image upload handling
    $uploaded_image = null; // Default value if no image is uploaded
    if (!empty($image['name'])) {
        $image_ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $allowed_image_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($image_ext, $allowed_image_extensions)) {
            $_SESSION['error'] = 'Image must be a valid type (JPG, JPEG, PNG, GIF).';
                    header('Location: /BRBMS/admin/beach/manage.php?beach_id=' . $beach_id);

            exit();
        }

        $image_new_name = uniqid() . '.' . $image_ext;
        $image_path = '../../uploads/amenity_images/' . $image_new_name; // Adjust path as per structure

        // Ensure directory exists
        if (!is_dir('../../uploads/amenity_images')) {
            mkdir('../../uploads/amenity_images', 0777, true);
        }

        if (!move_uploaded_file($image['tmp_name'], $image_path)) {
            $_SESSION['error'] = 'Failed to upload the image.';
                    header('Location: /BRBMS/admin/beach/manage.php?beach_id=' . $beach_id);

            exit();
        }

        $uploaded_image = $image_new_name; // Store the new image name
    }

    // Insert into database
    try {
        $stmt = $db->prepare("
            INSERT INTO amenities (beach_id, amenity_type, name, description, price, capacity, quantity, image, availability_status)
            VALUES (:beach_id, :amenity_type, :name, :description, :price, :capacity, :quantity, :image, :availability_status)
        ");
        $stmt->execute([
            ':beach_id' => $beach_id,
            ':amenity_type' => $amenity_type,
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':capacity' => $capacity,
            ':quantity' => $quantity,
            ':image' => $uploaded_image, // Use the uploaded image or null
            ':availability_status' => $quantity > 0 ? 'available' : 'unavailable' // Status based on quantity
        ]);

        $_SESSION['success'] = "Amenity added successfully!";
        header("Location: /BRBMS/admin/amenities/manage.php");
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: /BRBMS/admin/amenities/manage.php");
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: /BRBMS/admin/amenities/manage.php");
}
