<?php
include '../../config/config.php';
include '../checkuser.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);


// Ensure session is started

// Check if a beach_id is passed via GET, and store it in the session if not already set
if (isset($_GET['beach_id'])) {
    $_SESSION['active_beach_id'] = $_GET['beach_id'];  // Store the selected beach ID in session
}

// Fetch the active beach ID from the session
$active_beach_id = isset($_SESSION['active_beach_id']) ? $_SESSION['active_beach_id'] : null;

// Check if the active beach ID is set in the session
if ($active_beach_id) {
    // Fetch the beach data for the active beach ID
    $query = "SELECT * FROM beaches WHERE beach_id = :beach_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':beach_id', $active_beach_id, PDO::PARAM_INT);
    $stmt->execute();
    $beach_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($beach_data) {
        
        // Fetch and display amenities for the active beach
        $stmt = $db->prepare("SELECT * FROM amenities WHERE beach_id = :beach_id");
        $stmt->bindParam(':beach_id', $active_beach_id, PDO::PARAM_INT);
        $stmt->execute();
        $amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group amenities by their type
        $grouped_amenities = [];
        foreach ($amenities as $amenity) {
            $grouped_amenities[$amenity['amenity_type']][] = $amenity;
        }
    }
} else {
    echo "Beach ID is not set.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Amenities</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .btn-success:hover {
        background-color: #28a745;
        border-color: #218838;
        transform: scale(1.05);
    }
</style>

<body>
<div class="container mt-5">
    <div class="text-center mt-5">
        <h1 class="display-5 fw-bold">Manage Amenities</h1>
        <p class="text-muted">Add, edit, or remove amenities with ease</p>
        <hr class="my-3" style="border: 2px solid #007bff; width: 80%; margin: auto;">
    </div>

    <button type="button" class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addAmenityModal">
        <i class="bi bi-plus-circle"></i> Add
    </button>
    

    <!-- Display Error and Success Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <br>
    <?php foreach ($grouped_amenities as $type => $amenities_by_type): ?>
        
        <br>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Manage <?= ucfirst($type) ?>s</h4>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="add-row" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Price</th>
                                <th scope="col">Capacity</th>
                                <th scope="col">Available Quantity</th>
                                <th scope="col">Availability</th>
                                <th style="width: 15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($amenities_by_type) > 0): ?>
                                <?php foreach ($amenities_by_type as $amenity): ?>
                                    <tr>
                                        <td>
                                            <img src="../uploads/amenity_images/<?= htmlspecialchars($amenity['image']) ?>" 
                                                 alt="Amenity Image" 
                                                 style="width: 100px; height: auto;">
                                        </td>
                                        <td><?= htmlspecialchars($amenity['name']) ?></td>
                                        <td><?= htmlspecialchars($amenity['description']) ?></td>
                                        <td>₱<?= htmlspecialchars($amenity['price']) ?></td>
                                        <td><?= htmlspecialchars($amenity['capacity']) ?></td>
                                        <td><?= htmlspecialchars($amenity['quantity']) ?></td>
                                        <td><?= ($amenity['quantity'] > 0) ? 'Available' : 'Unavailable' ?></td>
                                        <td>
                                            <div class="form-button-action">
                                                                                        <!-- Edit Amenity Button with Tooltip and Modal Integration -->
                                            <button type="button" 
                                                    class="btn btn-link btn-primary btn-lg" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#updateAmenityModal"
                                                    data-id="<?= $amenity['amenity_id'] ?>"
                                                    data-type="<?= $amenity['amenity_type'] ?>"
                                                    data-name="<?= $amenity['name'] ?>"
                                                    data-description="<?= $amenity['description'] ?>"
                                                    data-price="<?= $amenity['price'] ?>"
                                                    data-capacity="<?= $amenity['capacity'] ?>"
                                                    data-quantity="<?= $amenity['quantity'] ?>"
                                                    data-image="<?= $amenity['image'] ?>"
                                                    title="Edit Amenity">
                                                <i class="fa fa-edit"></i>
                                            </button>


                                                <!-- Delete Button -->
                                                                                        <!-- Delete Button -->
                                            <button type="button" data-bs-toggle="tooltip" title="Remove Amenity" class="btn btn-link btn-danger" 
                                                onclick="if(confirm('Are you sure you want to delete this amenity?')) { window.location.href='../php/amenities/delete.php?id=<?= $amenity['amenity_id'] ?>'; }">
                                                <i class="fa fa-times"></i>
                                            </button>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Message when no amenities exist -->
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No added amenities yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div> <!-- End of table-responsive -->
            </div> <!-- End of card -->
        </div> <!-- End of col-md-12 -->
    <?php endforeach; ?>
</div> <!-- End of container -->


    <!-- Add Amenity Modal -->
    <div class="modal fade" id="addAmenityModal" tabindex="-1" aria-labelledby="addAmenityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAmenityModalLabel">Add New Amenity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../php/amenities/add.php" method="POST" enctype="multipart/form-data">
                        <!-- Hidden beach_id input -->
                        <input type="hidden" name="beach_id" value="<?= isset($active_beach_id) ? $active_beach_id : ''; ?>">



                        <!-- Amenity Type -->
                        <div class="mb-3">
                            <label for="amenity_type" class="form-label">Amenity Type</label>
                            <select class="form-control" id="amenity_type" name="amenity_type" required>
                                <option value="" disabled selected>Select Amenity Type</option>
                                <option value="cottage">Cottage</option>
                                <option value="room">Room</option>
                                <option value="pool">Pool</option>
                                <option value="parking lot">Parking Lot</option>
                                <option value="other">Others</option>
                            </select>
                        </div>

                        <!-- Amenity Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Amenity Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter amenity name" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter amenity description" required></textarea>
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required>
                        </div>

                        <!-- Capacity -->
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" placeholder="Enter capacity" required>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" required>
                        </div>

                        <!-- Image -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Amenity Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" name="add_amenity" class="btn btn-primary">Add Amenity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Update Amenity Modal -->
<div class="modal fade" id="updateAmenityModal" tabindex="-1" aria-labelledby="updateAmenityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateAmenityModalLabel">Update Amenity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../php/amenities/update.php" method="POST" enctype="multipart/form-data">
                    <!-- Hidden Amenity ID -->
                    <input type="hidden" name="amenity_id" id="amenity_id">
                    
                    <!-- Amenity Type -->
                    <div class="mb-3">
                        <label for="update_amenity_type" class="form-label">Amenity Type</label>
                        <select class="form-control" id="update_amenity_type" name="amenity_type" required>
                            <option value="cottage">Cottage</option>
                            <option value="room">Room</option>
                            <option value="pool">Pool</option>
                            <option value="parking lot">Parking Lot</option>
                            <option value="others">Others</option>
                        </select>
                    </div>

                    <!-- Amenity Name -->
                    <div class="mb-3">
                        <label for="update_name" class="form-label">Amenity Name</label>
                        <input type="text" class="form-control" id="update_name" name="name" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="update_description" class="form-label">Description</label>
                        <textarea class="form-control" id="update_description" name="description" rows="4" required></textarea>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="update_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="update_price" name="price" required>
                    </div>

                    <!-- Capacity -->
                    <div class="mb-3">
                        <label for="update_capacity" class="form-label">Capacity</label>
                        <input type="number" class="form-control" id="update_capacity" name="capacity" required>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-3">
                        <label for="update_quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="update_quantity" name="quantity" required>
                    </div>

                    <!-- Current Image -->
                    <div class="mb-3">
                        <label for="current_image" class="form-label">Current Amenity Image</label>
                        <img id="current_image" class="img-fluid" src="" alt="Current Image" />
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label for="update_image" class="form-label">New Amenity Image</label>
                        <input type="file" class="form-control" id="update_image" name="image" accept="image/*">
                        <img id="image_preview" class="img-fluid mt-2" src="" alt="Image Preview" />
                    </div>

                    <button type="submit" name= "update_amenity" class="btn btn-primary">Update Amenity</button>
                </form>
            </div>
        </div>
    </div>
</div>


    

    <div class="modal-dialog modal-dialog-centered modal-sm"> <!-- Added 'modal-sm' for smaller size -->


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});


</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Select all the update buttons for amenities
        const updateButtons = document.querySelectorAll('button[data-bs-target="#updateAmenityModal"]');
        
        updateButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Get the modal and populate the fields with the button's data attributes
                const modal = document.querySelector('#updateAmenityModal');
                
                // Populate the form fields in the modal
                modal.querySelector('#amenity_id').value = button.dataset.id;
                modal.querySelector('#update_amenity_type').value = button.dataset.type;
                modal.querySelector('#update_name').value = button.dataset.name;
                modal.querySelector('#update_description').value = button.dataset.description;
                modal.querySelector('#update_price').value = button.dataset.price;
                modal.querySelector('#update_capacity').value = button.dataset.capacity;
                modal.querySelector('#update_quantity').value = button.dataset.quantity;

                // Set current images (to show them before uploading a new one)
                modal.querySelector('#current_image').src = `../uploads/amenity_images/${button.dataset.image}`;
                
                // Reset the new image preview
                modal.querySelector('#image_preview').src = '';
            });
        });

        // Live preview for image uploads in the update modal
        document.querySelector('#update_image').addEventListener('change', function () {
            const reader = new FileReader();
            reader.onload = e => document.querySelector('#image_preview').src = e.target.result;
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>



</body>
</html>
