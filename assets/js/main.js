(() => {
  'use strict'

  const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
function togglePassword() {
  var passwordField = document.getElementById("password");
  var eyeIcon = document.getElementById("eyeIcon");

  // Toggle password visibility
  if (passwordField.type === "password") {
      passwordField.type = "text";
      eyeIcon.classList.remove("bi-eye");
      eyeIcon.classList.add("bi-eye-slash");
  } else {
      passwordField.type = "password";
      eyeIcon.classList.remove("bi-eye-slash");
      eyeIcon.classList.add("bi-eye");
  }
}