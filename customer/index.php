<?php
require '../config/config.php';
include 'checkuser.php';
 // Include your database connection
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - BRBMS</title>
    <meta name="description" content="Beach Resort Bazaar Management System">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/Nunito.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/Articles-Cards-images.css">
    <link rel="stylesheet" href="../assets/css/Navbar-Right-Links-icons.css">
    <link rel="stylesheet" href="../assets/css/Pricing-Clean-badges.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-expand bg-white shadow mb-0 topbar">
                    <div class="container-fluid"><a class="navbar-brand d-flex align-items-center" href="#"><span>BRBMS</span></a>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search amenities..."><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                        </form>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link active" href="index.php"><br><span style="color: rgb(62, 74, 89); background-color: initial;">Home</span><br><br></a></li>
                            <li class="nav-item"><a class="nav-link" href="#beach"><br><span style="color: rgb(62, 74, 89); background-color: initial;">Beaches</span><br><br><br></a></li>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><i class="fas fa-search"></i></a>
                                <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in" aria-labelledby="searchDropdown">
                                    <form class="me-auto navbar-search w-100">
                                        <div class="input-group"><input class="bg-light border-0 form-control small" type="text" placeholder="Search for ..."><button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button></div>
                                    </form>
                                </div>
                            </li>
                           
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small">Valerie Luna</span><img class="border rounded-circle img-profile" src="../assets/img/avatars/avatar1.jpeg"></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a><a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Settings</a><a class="dropdown-item" href="#"><i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Activity log</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <section>
                    <div style="height: 600px;background: url(&quot;https://observer.com/wp-content/uploads/sites/2/2018/11/tmpc-lhi-anegada.jpg?resize=50&quot;) center / cover;"></div>
                    <div class="container h-100 position-relative" style="top: -50px;">
                        <div class="row gy-5 gy-lg-0 row-cols-1 row-cols-md-2 row-cols-lg-3"></div>
                    </div>
                </section>
                <section id= "beach">
                <div class="container py-4 py-xl-5">
    <div class="row mb-5">
        <div class="col-md-8 col-xl-6 text-center mx-auto">
            <h2>Explore Beaches</h2>
            <p class="w-lg-50">Discover beautiful beaches and enjoy your vacation!</p>
        </div>
    </div>
    <div class="row gy-4 row-cols-1 row-cols-md-2 row-cols-xl-3">
        <?php
        try {
            include '../config/config.php';
            // Fetch data from the 'beaches' table
            $sql = "SELECT * FROM beaches";
            $stmt = $db->query($sql);

            // Loop through each beach record and display it
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '
                    <div class="col-xl-4">
                        <div class="card">
                            <img class="card-img-top w-100 d-block fit-cover" style="height: 200px;" src="../admin/php/uploads/' . $row['image'] . '">
                            <div class="card-body p-4">
                                <p class="text-primary card-text mb-0">Available</p>
                                <h4 class="card-title">' . $row['beach_name'] . '</h4>
                                <p class="card-text">' . $row['description'] . '</p>
                                <p class="card-text"><strong><span style="color: rgb(0, 0, 0);">Location: </span></strong>' . $row['location'] . '</p>
                                <a class="btn btn-primary w-100" href="index-1.php?id='.$row['beach_id'].'">View Details</a>   
                                
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo "No beaches available.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div>
</div>
</section>

            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © BRBMS 2024</span></div>
                </div>
            </footer>
        </div>
        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="signin">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Sign In</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Here you can sign in.</p>
                    <form class="row g-3 needs-validation" action="../BRBMS/../assets/php/Auth.php" method="POST">
                        <div class="form-floating mb-3" >
  <input
    type="text"
    class="form-control"
    id="floatingInput"
    name="username"
    placeholder=""
    required
  />
  <label for="floatingInput">Username</label>
  <div class="valid-feedback">Looks good!</div>
  <div class="invalid-feedback">Please enter a message in the email.</div>
</div>
<div class="form-floating">
  <input
    type="password"
    class="form-control"
    id="floatingPassword"
    name="password"
    placeholder=""
    required
  />
  <label for="floatingPassword">Password</label>
  <div class="valid-feedback">Looks good!</div>
  <div class="invalid-feedback">Please enter a message in the email.</div>
</div>
<button class="btn btn-primary" type="submit" name="login">Sign In</button></form>
                </div>
                <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="signup">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Sign Up</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Here you can sign up.</p>
                    <form class="row g-3 needs-validation" action="../BRBMS/../assets/php/Auth.php" method="POST">
                        <div class="form-floating mb-2" >
  <input
    type="text"
    class="form-control"
    id="floatingInput"
    name="username"
    placeholder="Enter Username"
    required
  />
  <label for="floatingInput">Username</label>
  <div class="valid-feedback">Looks good!</div>
  <div class="invalid-feedback">Please enter a message in the email.</div>
</div>

<div class="form-floating">
  <input
    type="password"
    class="form-control"
    id="floatingPassword"
    placeholder="Enter Password"
    name="password"
    required
  />
  <label for="floatingPassword">Password</label>
  <div class="valid-feedback">Looks good!</div>
  <span class="input-group-text" id="togglePassword" onclick="togglePassword()">
    <i class="bi bi-eye" id="eyeIcon"></i>
    </span>
  <div class="invalid-feedback">Please enter Password.</div>
</div>

<div class="form-floating mb-2">
  <input
    type="text"
    class="form-control"
    id="floatingInput"
    placeholder="Enter Full Name"
    name="full_name"
    required
  />
  <label for="floatingInput">Fullname</label>
  <div class="valid-feedback">Looks good!</div>
  <div class="invalid-feedback">Please enter a message in the email.</div>
</div>

<div class="form-floating mb-2">
  <input
    type="email"
    class="form-control"
    id="floatingInput"
    placeholder="Enter your email"
    name="email"
    required
  />
  <label for="floatingInput">Email</label>
  <div class="valid-feedback">Looks good!</div>
  <div class="invalid-feedback">Please enter a message in the email.</div>
</div>

<div class="form-floating mb-2">
  <input
    type="text"
    class="form-control"
    id="floatingInput"
    name="contact_number"
    placeholder="Enter your contact number"
    required
  />
  <label for="floatingInput">Phone</label>
  <div class="valid-feedback">Looks good!</div>
  <div class="invalid-feedback">Please enter a message in the Phone number.</div>
</div>

<div class="form-floating mb-2">
  <select class="form-select" id="floatingSelect" name="gender" aria-label="Floating label select gender" required>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
  </select>
  <label for="floatingSelect">Gender</label>
</div>
<div class="form-floating mb-2">
    <select class="form-select" id="floatingSelect" name="user_role" aria-label="Floating label select gender" required>
      <option value="owner">Owner</option>
      <option value="Customer">Customer</option>
    </select>
    <label for="floatingSelect">User Role</label>
  </div>

<button class="btn btn-primary" type="submit" name="signup">Sign Up</button>
</form>
                </div>
                <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../assets/js/bs-init.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/theme.js"></script>
   
</body>

</html>
<script>
    document.getElementById('amenities-search').addEventListener('input', function () {
        const searchValue = this.value; // Get the value of the input field
        const beachId = '<?php echo isset($_SESSION['beach_id']) ? htmlspecialchars($_SESSION['beach_id']) : ''; ?>';
        
        // Create an AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `customer\php\amenities\check availability.php?search=${searchValue}&beach_id=${beachId}`, true);
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