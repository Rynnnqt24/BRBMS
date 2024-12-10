<?php
include '../reservation/add.php'
?>


<form method="POST" action="add.php">
    <div class="form-group">
        <label for="customer_name">Customer Name</label>
        <input type="text" name="customer_name" id="customer_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="amenity_id">Amenity</label>
        <select name="amenity_id" id="amenity_id" class="form-control" required>
            <!-- Dynamically load amenities from the database -->
            <?php
            // Fetch amenities
            $stmt = $db->prepare("SELECT amenity_id, amenity_name FROM amenities");
            $stmt->execute();
            $amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($amenities as $amenity) {
                echo "<option value='" . $amenity['amenity_id'] . "'>" . $amenity['amenity_name'] . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="checkin_date">Check-in Date</label>
        <input type="date" name="checkin_date" id="checkin_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="checkout_date">Check-out Date</label>
        <input type="date" name="checkout_date" id="checkout_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" id="quantity" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="total_amount">Total Amount</label>
        <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" required>
    </div>
    <button type="submit" class="btn btn-success mt-2">Create Reservation</button>
</form>
