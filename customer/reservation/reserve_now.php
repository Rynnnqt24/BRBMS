<div class="container mt-5">
    <h1 class="text-center">Reservation Form</h1>
    <form id="reservationForm" method="POST" action="submit_reservation.php" onsubmit="showLoadingSpinner()">
        <!-- Existing form fields like customer name, address, etc. -->

        <!-- Amenity Selection (existing code) -->
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
                                data-quantity="<?= htmlspecialchars($amenity['quantity']); ?>"
                                data-image="<?= htmlspecialchars($amenity['image']); ?>">
                                <?= htmlspecialchars($amenity['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No amenities available</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Additional form fields like check-in date, payment status, etc. -->

        <!-- Reserve Now Button Form -->
        <form action="reserve_now.php" method="POST" id="reserveNowForm">
            <input type="hidden" name="amenity_id" id="hiddenAmenityId">
            <input type="hidden" name="price" id="hiddenPrice">
            <input type="hidden" name="quantity" id="hiddenQuantity">
            <input type="hidden" name="quantity_to_reserve" id="hiddenQuantityToReserve">
            <input type="hidden" name="total_amount" id="hiddenTotalAmount">
            <button type="submit" class="btn btn-success">Reserve Now</button>
        </form>
    </div>

    <!-- Loading Spinner (as before) -->
    <div id="loadingSpinner" class="spinner-border text-primary" style="display:none;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script>
    // This function handles updating the reservation details when an amenity is selected
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

        // Update the hidden fields for the "Reserve Now" form
        document.getElementById("hiddenAmenityId").value = selectedOption.value;
        document.getElementById("hiddenPrice").value = price;
        document.getElementById("hiddenQuantity").value = quantity;
        document.getElementById("hiddenQuantityToReserve").value = document.getElementById("quantity_to_reserve").value;
        document.getElementById("hiddenTotalAmount").value = document.getElementById("total_amount").value;
    }

    // This function calculates the total amount when the user enters the quantity to reserve
    function updateTotalAmount() {
        var quantityToReserve = document.getElementById("quantity_to_reserve").value;
        var pricePerUnit = document.getElementById("price_display").innerText.replace("₱", "");
        var totalAmount = quantityToReserve * parseFloat(pricePerUnit);

        document.getElementById("total_amount").value = totalAmount;
    }

    // Optionally, you can add a loading spinner when the form is being submitted
    function showLoadingSpinner() {
        document.getElementById("loadingSpinner").style.display = "block";
    }
</script>
