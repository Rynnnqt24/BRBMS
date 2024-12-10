<?php
// Ensure session is started
include_once 'checkuser.php';

// Check if active_beach_id is set
if (isset($_GET['beach_id'])) {
    $beach_id = $_GET['beach_id'];

    // Debug: Check if the ID is received correctly
    // echo 'Active Beach ID: ' . $beach_id; // Uncomment for debugging purposes
    // exit(); // Remove this after debugging

    // Assuming you already have a valid PDO database connection
    // Use $pdo for all database queries consistently
    $query = "SELECT * FROM beaches WHERE beach_id = :beach_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);
    $stmt->execute();
    $beach = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$beach) {
        echo "Beach not found!";
        exit();
    }

    // Fetch the amenities for the selected beach
    $queryAmenities = "SELECT * FROM amenities WHERE beach_id = :beach_id";
    $stmtAmenities = $db->prepare($queryAmenities);
    $stmtAmenities->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);
    $stmtAmenities->execute();
    $amenities = $stmtAmenities->fetchAll(PDO::FETCH_ASSOC);

} else {
    echo "Beach ID not provided!";
    exit();

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beach Resort Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/cropperjs/dist/cropper.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/cropperjs/dist/cropper.min.js"></script>
    <style>
    .profile-header {
        display: flex;
        justify-content: space-between;  /* Align text and button to opposite sides */
        align-items: center;  /* Vertically align */
        margin-bottom: 30px;
    }

    .profile-header h3 {
        margin: 0;  /* Remove any default margin from the heading */
    }

    /* Container for the button */
    .edit-btn-container {
        display: flex; /* Flexbox for alignment */
        justify-content: flex-start; /* Align button to the left */
        align-items: center; /* Vertically align button if necessary */
        margin-bottom: 15px; /* Space below button */
    }

    /* Style for the button */
    .action-btn {
        background-color: #f8b400;
        color: white;
        border-radius: 30px;
        padding: 6px 12px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        margin: 0;
    }

    .action-btn i {
        font-size: 18px;
        margin-right: 8px;
    }

    .action-btn:hover {
        background-color: #f57c00;
        text-decoration: none;
    }

    .action-btn:focus {
        outline: none;
    }
    .card-img-top {
            object-fit: cover;
            height: 250px;
        }
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .gcash-img {
            width: 100px; /* Adjust size as necessary */
            height: auto;
            border-radius: 8px;
        }
         /* Custom styles for the Edit button */
.action-btn {
    background-color: #f8b400; /* A yellow color */
    color: white;
    border-radius: 20px; /* Rounded corners */
    padding: 5px 12px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    margin-left: 0; /* Align to the left of the container */
    margin-top: 10px; /* Adds some space from top */
}

.action-btn i {
    margin-right: 5px; /* Space between icon and text */
    font-size: 16px; /* Icon size */
}

.action-btn:hover {
    background-color: #f57c00; /* Darker yellow when hovered */
    text-decoration: none; /* Remove text decoration on hover */
}

.action-btn:focus {
    outline: none; /* Remove focus outline */
}
</style>

</head>
<body>

<div class="container my-5">
    <div class="profile-header">
        <h3 class="text-muted">Beach Resort Profile</h3>
    </div>
    <div class="edit-btn-container">
    <button class="btn btn-warning btn-sm action-btn" data-bs-toggle="modal" data-bs-target="#updateBeachModal" 
        data-id="<?= isset($beach['beach_id']) ? $beach['beach_id'] : '' ?>"
        data-name="<?= htmlspecialchars($beach['beach_name']) ?>"
        data-description="<?= htmlspecialchars($beach['description']) ?>"
        data-location="<?= htmlspecialchars($beach['location']) ?>"
        data-longitude="<?= htmlspecialchars($beach['longitude']) ?>"
        data-latitude="<?= htmlspecialchars($beach['latitude']) ?>"
        data-image="<?= htmlspecialchars($beach['image']) ?>"
        data-gcash_qr_code="<?= htmlspecialchars($beach['gcash_qr_code']) ?>">

        <i class="fas fa-edit"></i> Edit
    </button>

    </div>
    <div class="row">
        <!-- Profile Section -->
        <div class="col-md-4">
            <div class="card profile-card">
                <img src="../php/uploads/<?= htmlspecialchars($beach['image']) ?>" class="card-img-top" alt="Beach Image">
                <div class="card-body">
                    <h5 class="card-title"><?= ucwords(htmlspecialchars($beach['beach_name'])) ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($beach['description'])) ?></p>
                    <p><strong>Location:</strong> <?= ucwords(htmlspecialchars($beach['location'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Amenities Section -->
        <div class="col-md-4">
            <div class="card profile-card">
                <div class="card-header">
                    <h5 class="section-title">Amenities</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php
                        // Loop through the amenities and display them
                        foreach ($amenities as $amenity) {
                            
                            echo '<li class="list-group-item">' .ucwords(htmlspecialchars($amenity['amenity_type'])).': ' . ucwords(htmlspecialchars($amenity['name'])) . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Location Section -->
        <div class="col-md-4">
            <div class="card profile-card">
                <div class="card-header">
                    <h5 class="section-title">Location</h5>
                </div>
                <div class="card-body">
                    <p>Find us at:</p>
                    <p><strong><?= ucwords(htmlspecialchars($beach['location'])) ?></strong></p>
                    <!-- Embedded Google Map -->
                    <iframe src="https://www.google.com/maps/embed/v1/place?q=<?= urlencode($beach['location']) ?>&key=YOUR_GOOGLE_MAPS_API_KEY" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>

    <br><br>
  <!-- GCash Section -->
<div class="card profile-card">
    <div class="card-header">
        <h5 class="section-title">GCash</h5>
        
    </div>
    
    <div class="card-body">
        <!-- GCash Name Field -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="mb-0">Gcash Name: <?= htmlspecialchars($beach['gcash_name']) ?: 'No GCash Name provided.' ?></p>
          
        </div>

        <!-- GCash Phone Number Field -->
        <div class="d-flex justify-content-between align-items-center mb-3">
           <p class="mb-0">Gcash Phone Number: <?= htmlspecialchars($beach['gcash_phone_number']) ?: 'No GCash Phone Number provided.' ?></p>
           <!-- GCash Phone Number Update Button -->

        </div>

        <!-- GCash QR Code Field -->
        <p>Gcash QR Code:</p>
        <div class="d-flex justify-content-between align-items-center">
       
            <?php if (!empty($beach['gcash_qr_code'])): ?>
        
                <img src="../php/uploads/<?= htmlspecialchars($beach['gcash_qr_code']) ?>" alt="GCash QR Code" class="gcash-img">
            <?php else: ?>
                <p>No GCash QR code uploaded yet.</p>
            <?php endif; ?>
            <!-- GCash QR Code Update Button -->
           
            <button type="button" class="btn btn-link btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" 
    onclick="openUpdateModal('gcash_phone_number', '<?= $beach['beach_id'] ?>', '<?= $beach['gcash_phone_number'] ?>', '<?= $beach['gcash_name'] ?>')">
    <i class="fa fa-edit"></i> 
</button>



        </div>
        
    </div>
    
</div>


<div class="modal fade" id="updateBeachModal" tabindex="-1" aria-labelledby="updateBeachModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateBeachModalLabel">Update Beach</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../php/beach/update.php" method="POST" enctype="multipart/form-data">
                    <!-- Beach Name -->
                    <div class="mb-3">
                        <label for="update_beach_name" class="form-label">Beach Name</label>
                        <input type="text" class="form-control" id="update_beach_name" name="beach_name" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="update_description" class="form-label">Description</label>
                        <textarea class="form-control" id="update_description" name="description" rows="4" required></textarea>
                    </div>

                    <!-- Location -->
                    <div class="mb-3">
                        <label for="update_location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="update_location" name="location" required>
                    </div>

                    <!-- Latitude -->
                    <div class="mb-3">
                        <label for="update_latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="update_latitude" name="latitude" required>
                    </div>

                    <!-- Longitude -->
                    <div class="mb-3">
                        <label for="update_longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="update_longitude" name="longitude" required>
                    </div>


                    <div class="mb-3">
                 <label for="current_qr_code" class="form-label">Current QR Code</label>
                 <div>
                  <img id="current_qr_code" src="" alt="Current QR Code" class="img-fluid" style="max-height: 200px; max-width: 100%; object-fit: contain;">
                  </div>
                </div>

                   

                    <!-- Update QR Code -->
                    <div class="mb-3">
                        <label for="update_qr_code" class="form-label">New QR Code</label>
                        <input type="file" class="form-control" id="update_qr_code" name="gcash_qr_code" accept="image/*">
                        <img id="qr_code_preview" class="img-fluid mt-2" src="" alt="New QR Code Preview" style="max-height: 200px; max-width: 100%; object-fit: contain;">
                    </div>

                    <!-- Hidden Beach ID -->
                    <input type="hidden" id="beach_id" name="beach_id">
                    <div class="mb-3">

                      <!-- Current Image -->
                      <div class="mb-3">
                        <label for="current_image" class="form-label">Current Beach Image</label>
                        <div>
                            <img id="current_image" src="" alt="Current Beach Image" class="img-fluid" style="max-height: 200px; max-width: 100%; object-fit: contain;">
                        </div>
                    </div>

                   <label for="update_image" class="form-label">Update Beach Image</label>
                <input type="file" class="form-control" id="update_image" name="image" accept="image/*" onchange="loadImage(event)">
                <div class="mt-3">
                    <img id="imagePreview" src="" style="max-width: 100%; display: none;" alt="Image Preview">
                </div>
            </div>

            <!-- Add a hidden field for the cropped image -->
            <input type="hidden" id="cropped_image" name="cropped_image">
                

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" name="update_beach" class="btn btn-primary">Update Beach</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<<div class="modal-body">
    <form action="../php/beach/update_gcash.php" method="POST" enctype="multipart/form-data">
        <!-- Hidden field to hold the beach_id -->
        <input type="hidden" name="beach_id" id="beach_id" value="">

        <!-- GCash Name -->
        <div class="mb-3">
            <label for="gcash_name" class="form-label">GCash Name:</label>
            <input type="text" class="form-control" id="gcash_name" name="gcash_name" placeholder="Enter GCash Name" required>
        </div>

        <!-- GCash Phone Number -->
        <div class="mb-3">
            <label for="gcash_phone_number" class="form-label">GCash Phone Number:</label>
            <input type="text" class="form-control" id="gcash_phone_number" name="gcash_phone_number" placeholder="Enter 11-digit Phone Number" required>
        </div>

        <!-- GCash QR Code -->
        <div class="mb-3">
            <label for="gcash_qr_code" class="form-label">GCash QR Code:</label>
            <input type="file" class="form-control" id="gcash_qr_code" name="gcash_qr_code" accept="image/*">
            <div class="mt-2">
                <p>Current QR Code:</p>
                <img id="gcash_qr_code_img" src="" alt="GCash QR Code" class="img-fluid border rounded" width="150">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-end">
            <button type="submit" name="update_gcash" class="btn btn-success w-100">Save Changes</button>
        </div>
    </form>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
       document.addEventListener('DOMContentLoaded', () => {
    const updateButtons = document.querySelectorAll('button[data-bs-target="#updateBeachModal"]');
    updateButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = document.querySelector('#updateBeachModal');
            modal.querySelector('#update_beach_name').value = button.dataset.name;
            modal.querySelector('#update_description').value = button.dataset.description;
            modal.querySelector('#update_location').value = button.dataset.location;
            modal.querySelector('#update_latitude').value = button.dataset.latitude;
            modal.querySelector('#update_longitude').value = button.dataset.longitude;
            modal.querySelector('#beach_id').value = button.dataset.id;

           // Set current images
           modal.querySelector('#current_image').src = `../php/uploads/${button.dataset.image}`;
            modal.querySelector('#current_qr_code').src = `../php/uploads/${button.dataset.gcash_qr_code}`;

            // Reset new image previews
            modal.querySelector('#image_preview').src = '';
            modal.querySelector('#qr_code_preview').src = '';
        });
    });

    // Live preview for image uploads
    document.querySelector('#update_image').addEventListener('change', function () {
        const reader = new FileReader();
        reader.onload = e => document.querySelector('#image_preview').src = e.target.result;
        reader.readAsDataURL(this.files[0]);
    });

    document.querySelector('#update_qr_code').addEventListener('change', function () {
        const reader = new FileReader();
        reader.onload = e => document.querySelector('#qr_code_preview').src = e.target.result;
        reader.readAsDataURL(this.files[0]);
    });
});


    </script>

<script>
    let cropper;

    function loadImage(event) {
        const image = document.getElementById('imagePreview');
        image.src = URL.createObjectURL(event.target.files[0]);
        image.style.display = 'block';

        // Initialize the cropper
        if (cropper) {
            cropper.destroy();
        }

        cropper = new Cropper(image, {
            aspectRatio: 16 / 9,  // Set the aspect ratio for cropping (optional)
            viewMode: 1,          // Limits the cropping area
            autoCropArea: 0.65,   // Set initial crop area size
            responsive: true,
            crop: function(event) {
                // Get the cropped image as base64
                const canvas = cropper.getCroppedCanvas();
                document.getElementById('cropped_image').value = canvas.toDataURL('image/png');
            }
        });
    }

    // Optional: Add a button to reset the crop
    function resetCrop() {
        const image = document.getElementById('imagePreview');
        cropper.reset();
    }

    function openUpdateModal(beach_id, gcash_phone_number, gcash_name, gcash_qr_code) {
    // Set values in the form
    document.getElementById('beach_id').value = beach_id;  // Set the beach_id
    document.getElementById('gcash_phone_number').value = gcash_phone_number;
    document.getElementById('gcash_name').value = gcash_name;
    // If the QR code is present, you can show the image
    if (gcash_qr_code) {
        document.getElementById('gcash_qr_code_img').src = gcash_qr_code;
    }
}


</script>



</body>
</html>
