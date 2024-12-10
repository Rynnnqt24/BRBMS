<?php

if (!isset($_SESSION['beach_id'])) {
    die("Error: 'beach_id' is not set in the session.");
} else {
    echo "Debug: beach_id = " . htmlspecialchars($_SESSION['beach_id']);
}



?>
<form action="#" method="POST" id="search-form">
<!-- The form that calls the backend script -->
<div class="row justify-content-center">
    <div class="col-lg-10 col-md-5 bg-transparent p-2 rounded">
        <div class="d-flex flex-wrap justify-content-between mb-4">
            <!-- Search Bar -->
            <input type="hidden" name="beach_id" value="<?php echo htmlspecialchars($_SESSION['beach_id']); ?>">

            <!-- Check-in Date -->
            <div class="flex-grow-1 me-3 mb-3">
                <label for="checkin-date" class="form-label">Check-in Date</label>
                <input type="date" name="checkin_date" id="checkin-date" class="form-control">
            </div>

            <!-- Amenities Select -->
            <div class="flex-grow-1 me-3 mb-3">
                <label for="amenities" class="form-label">Select Amenities</label>
                <select name="amenities" id="amenities" class="form-select">
                    <option value="cottage">Cottage</option>
                    <option value="room">Room</option>
                    <option value="pool">Swimming Pool</option>
                    <option value="parking">Parking Lot</option>
                </select>
            </div>

            <!-- Search Button -->
            <div class="flex-grow-1 me-3 mb-3">
                <label for="checkin-date" class="form-label"></label>
                <button type="submit" name="check_availability" class="btn btn-primary w-100 mt-2">Search Availability</button>
            </div>
        </div>

        <!-- Availability Message -->
        <div id="availability-message" class="mt-3"></div>
    </div>
</div>
</form>

<script>
    document.getElementById('amenities-search').addEventListener('input', function () {
        const searchValue = this.value; // Get the value of the input field
        const beachId = '<?php echo isset($_SESSION['beach_id']) ? htmlspecialchars($_SESSION['beach_id']) : ''; ?>';
        
        // Create an AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `check_availability.php?search=${searchValue}&beach_id=${beachId}`, true);
        console.log(xhr.responseText);

        xhr.onload = function () {
            if (xhr.status === 200) {
                // Update the availability message with the response
                document.getElementById('availability-message').innerHTML = xhr.responseText;
            } else {
                document.getElementById('availability-message').innerHTML = 'Error loading results.';
            }
        };

        xhr.send();
    });
</script>
