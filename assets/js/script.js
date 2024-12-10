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
           modal.querySelector('#current_image').src = `../assets/uploads/${button.dataset.image}`;
            modal.querySelector('#current_qr_code').src = `../assets/uploads/${button.dataset.gcash_qr_code}`;

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