<?php
// Connect to the database (ensure your DB credentials are correct)
include '../../config/config.php';

// Fetch amenities for the dropdown (assuming you have an amenities table)
$query = "SELECT amenity_id, name, price, quantity, image FROM amenities";
$stmt = $db->prepare($query);
$stmt->execute();
$amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Reservation Form</h1>
        <form id="reservationForm" method="POST" action="submit_reservation.php" onsubmit="showLoadingSpinner()">
            <div class="row mb-3">
                <label for="customer_name" class="col-sm-2 col-form-label">Customer Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="customer_address" class="col-sm-2 col-form-label">Address</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="customer_address" name="customer_address" required></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label for="contact_number" class="col-sm-2 col-form-label">Contact Number</label>
                <div class="col-sm-10">
                    <input type="tel" class="form-control" id="contact_number" name="contact_number" pattern="^\d{10}$" required>
                    <small class="form-text text-muted">Enter a valid 10-digit contact number</small>
                </div>
            </div>
            <div class="row mb-3">
                <label for="amenity_id" class="col-sm-2 col-form-label">Amenity</label>
                <div class="col-sm-10">
                    <select class="form-select" id="amenity_id" name="amenity_id" required onchange="updateAmenityDetails()">
                        <option value="" selected disabled>Select an Amenity</option>
                        <!-- PHP Loop to generate amenity options dynamically -->
                        <?php if (!empty($amenities)): ?>
    <?php foreach ($amenities as $amenity): ?>
        <option value="<?= htmlspecialchars($amenity['amenity_id']); ?>"
            data-price="<?= htmlspecialchars($amenity['price']); ?>"
            data-quantity="<?= htmlspecialchars($amenity['quantity']); ?>">
            <?= htmlspecialchars($amenity['name']); ?>
        </option>
    <?php endforeach; ?>
<?php else: ?>
    <option value="" disabled>No amenities available</option>
<?php endif; ?>

                    </select>
                </div>
            </div>
            <div id="amenityDetails" class="mt-3" style="display:none;">
                <div class="row mb-3">
                    <div class="col-sm-2">Price per Unit</div>
                    <div class="col-sm-10">
                        <span id="price_display">₱0</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-2">Available Quantity</div>
                    <div class="col-sm-10">
                        <span id="quantity_display">0</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-2">Quantity to Reserve</div>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="quantity_to_reserve" name="quantity_to_reserve" min="1" required onchange="updateTotalAmount()">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-2">Image</div>
                    <div class="col-sm-10">
                        <img id="amenity_image" src="" alt="Amenity Image" class="img-fluid" style="max-width: 200px;">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="checkin_date" class="col-sm-2 col-form-label">Check-in Date</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="checkin_date" name="checkin_date" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="checkout_date" class="col-sm-2 col-form-label">Check-out Date</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="checkout_date" name="checkout_date" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="payment_status" class="col-sm-2 col-form-label">Payment Status</label>
                <div class="col-sm-10">
                    <select class="form-select" id="payment_status" name="payment_status" required>
                        <option value="full">Full Payment</option>
                        <option value="partial">Partial Payment</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="payment_method" class="col-sm-2 col-form-label">Payment Method</label>
                <div class="col-sm-10">
                    <select class="form-select" id="payment_method" name="payment_method" required onchange="showPaymentModal()">
                        <option value="GCash">GCash</option>
                        <option value="Cash">Cash</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="total_amount" class="col-sm-2 col-form-label">Total Amount</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="total_amount" name="total_amount" readonly>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Reserve</button>
        </form>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="spinner-border text-primary" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>

    <!-- Modal for GCash -->
    <div class="modal" tabindex="-1" id="gcashModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">GCash Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>GCash Name:</strong> <span id="gcash_name_display"></span></p>
                    <p><strong>GCash Number:</strong> <span id="gcash_number_display"></span></p>
                    <p><strong>GCash QR Code:</strong> <img src="" id="gcash_qr_display" width="200" alt="QR Code"></p>
                    <p><strong>Total to Pay:</strong> ₱<span id="gcash_total_amount_display"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="donePaymentButton" onclick="donePaying()">Done Paying</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Cash -->
    <div class="modal" tabindex="-1" id="cashModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cash Payment Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Note:</strong> You need to pay within 24 hours to confirm your reservation. Please proceed with physical payment.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="donePaymentButtonCash" onclick="donePaying()">Okay</button>
                </div>
            </div>
        </div>
    </div>

    <script>
      function updateAmenityDetails() {
    var amenitySelect = document.getElementById("amenity_id");
    var selectedOption = amenitySelect.options[amenitySelect.selectedIndex];

    var price = selectedOption.getAttribute("data-price");
    var quantity = selectedOption.getAttribute("data-quantity");

    // Debugging output
    console.log("Price: " + price);
    console.log("Quantity: " + quantity);

    // Show amenity details
    document.getElementById("price_display").innerText = `₱${price}`;
    document.getElementById("quantity_display").innerText = quantity;

    document.getElementById("amenityDetails").style.display = "block";
    updateTotalAmount();
}


    </script>
</body>
</html>
