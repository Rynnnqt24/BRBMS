<?php
require 'config/config.php'; // Include your database connection
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - BRBMS</title>
    <meta name="description" content="Beach Resort Bazaar Management System">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Nunito.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Articles-Cards-images.css">
    <link rel="stylesheet" href="assets/css/Navbar-Right-Links-icons.css">
    <link rel="stylesheet" href="assets/css/Pricing-Clean-badges.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-expand bg-white shadow mb-0 topbar">
                    <div class="container-fluid"><a class="navbar-brand d-flex align-items-center" href="index.php"><span>BRBMS</span></a>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search amenities..."><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                        </form>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link active" href="index.php"><br><span style="color: rgb(62, 74, 89); background-color: initial;">Home</span><br><br></a></li>
                            <li class="nav-item"><a class="nav-link" href="index-1.html"><br><span style="color: rgb(62, 74, 89); background-color: initial;">Beaches</span><br><br><br></a></li>
                            <li class="nav-item"><a class="nav-link" href="#" data-bs-target="#signin" data-bs-toggle="modal"><br><span style="color: rgb(62, 74, 89); background-color: initial;">Login</span><br><br></a></li>
                            <li class="nav-item"><a class="nav-link" href="#" data-bs-target="#signup" data-bs-toggle="modal"><br><br><span style="color: rgb(62, 74, 89); background-color: initial;">Register</span><br><br><br><br></a></li>
                        </ul>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><i class="fas fa-search"></i></a>
                                <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in" aria-labelledby="searchDropdown">
                                    <form class="me-auto navbar-search w-100">
                                        <div class="input-group"><input class="bg-light border-0 form-control small" type="text" placeholder="Search for ..."><button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button></div>
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item dropdown no-arrow mx-1">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="badge bg-danger badge-counter">3+</span><i class="fas fa-bell fa-fw"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in">
                                        <h6 class="dropdown-header">alerts center</h6><a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="me-3">
                                                <div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 12, 2019</span>
                                                <p>A new monthly report is ready to download!</p>
                                            </div>
                                        </a><a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="me-3">
                                                <div class="bg-success icon-circle"><i class="fas fa-donate text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 7, 2019</span>
                                                <p>$290.29 has been deposited into your account!</p>
                                            </div>
                                        </a><a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="me-3">
                                                <div class="bg-warning icon-circle"><i class="fas fa-exclamation-triangle text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 2, 2019</span>
                                                <p>Spending Alert: We've noticed unusually high spending for your account.</p>
                                            </div>
                                        </a><a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                                    </div>
                                </div>
                            </li>
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small">Valerie Luna</span><img class="border rounded-circle img-profile" src="assets/img/avatars/avatar1.jpeg"></a>
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
                        <div class="row gy-5 gy-lg-0 row-cols-1 row-cols-md-2 row-cols-lg-3">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body pt-5 p-4">
                                        <div class="bs-icon-lg bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center position-absolute mb-3 bs-icon lg" style="top: -30px;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-bell">
                                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6"></path>
                                            </svg></div>
                                        <h4 class="card-title">Title</h4>
                                        <h6 class="text-muted card-subtitle mb-2">Subtitle</h6>
                                        <p class="card-text">Nullam id dolor id nibh ultricies vehicula ut id elit. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p>
                                    </div>
                                    <div class="card-footer p-4 py-3"><a href="#">Learn more&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-arrow-right">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"></path>
                                            </svg></a></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <div class="card-body pt-5 p-4">
                                        <div class="bs-icon-lg bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center position-absolute mb-3 bs-icon lg" style="top: -30px;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-bezier">
                                                <path fill-rule="evenodd" d="M0 10.5A1.5 1.5 0 0 1 1.5 9h1A1.5 1.5 0 0 1 4 10.5v1A1.5 1.5 0 0 1 2.5 13h-1A1.5 1.5 0 0 1 0 11.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm10.5.5A1.5 1.5 0 0 1 13.5 9h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM6 4.5A1.5 1.5 0 0 1 7.5 3h1A1.5 1.5 0 0 1 10 4.5v1A1.5 1.5 0 0 1 8.5 7h-1A1.5 1.5 0 0 1 6 5.5zM7.5 4a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"></path>
                                                <path d="M6 4.5H1.866a1 1 0 1 0 0 1h2.668A6.517 6.517 0 0 0 1.814 9H2.5c.123 0 .244.015.358.043a5.517 5.517 0 0 1 3.185-3.185A1.503 1.503 0 0 1 6 5.5zm3.957 1.358A1.5 1.5 0 0 0 10 5.5v-1h4.134a1 1 0 1 1 0 1h-2.668a6.517 6.517 0 0 1 2.72 3.5H13.5c-.123 0-.243.015-.358.043a5.517 5.517 0 0 0-3.185-3.185z"></path>
                                            </svg></div>
                                        <h4 class="card-title">Title</h4>
                                        <h6 class="text-muted card-subtitle mb-2">Subtitle</h6>
                                        <p class="card-text">Nullam id dolor id nibh ultricies vehicula ut id elit. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p>
                                    </div>
                                    <div class="card-footer p-4 py-3"><a href="#">Learn more&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-arrow-right">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"></path>
                                            </svg></a></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <div class="card-body pt-5 p-4">
                                        <div class="bs-icon-lg bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center position-absolute mb-3 bs-icon lg" style="top: -30px;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-flag">
                                                <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21.294 21.294 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21.317 21.317 0 0 0 14 7.655V1.222z"></path>
                                            </svg></div>
                                        <h4 class="card-title">Title</h4>
                                        <h6 class="text-muted card-subtitle mb-2">Subtitle</h6>
                                        <p class="card-text">Nullam id dolor id nibh ultricies vehicula ut id elit. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p>
                                    </div>
                                    <div class="card-footer p-4 py-3"><a href="#">Learn more&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-arrow-right">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"></path>
                                            </svg></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="container py-4 py-xl-5">
                    <div class="row mb-5">
                        <div class="col-md-8 col-xl-6 text-center mx-auto">
                            <h2>Explore Beaches</h2>
                            <p class="w-lg-50">Curae hendrerit donec commodo hendrerit egestas tempus, turpis facilisis nostra nunc. Vestibulum dui eget ultrices.</p>
                        </div>
                    </div>
                    <div class="row gy-4 row-cols-1 row-cols-md-2 row-cols-xl-3">
                        <div class="col-xl-4">
                            <div class="card"><img class="card-img-top w-100 d-block fit-cover" style="height: 200px;" src="https://cdn.bootstrapstudio.io/placeholders/1400x800.png">
                                <div class="card-body p-4">
                                    <p class="text-primary card-text mb-0">{status}</p>
                                    <h4 class="card-title">{title}</h4>
                                    <p class="card-text">{description}</p>
                                    <p class="card-text"><strong><span style="color: rgb(0, 0, 0);">Location: </span></strong>{description}</p><button class="btn btn-primary w-100" type="button">View Details</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card"><img class="card-img-top w-100 d-block fit-cover" style="height: 200px;" src="https://cdn.bootstrapstudio.io/placeholders/1400x800.png">
                                <div class="card-body p-4">
                                    <p class="text-primary card-text mb-0">{status}</p>
                                    <h4 class="card-title">{title}</h4>
                                    <p class="card-text">{description}</p>
                                    <p class="card-text"><strong><span style="color: rgb(0, 0, 0);">Location: </span></strong>{description}</p><button class="btn btn-primary w-100" type="button">View Details</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card"><img class="card-img-top w-100 d-block fit-cover" style="height: 200px;" src="https://cdn.bootstrapstudio.io/placeholders/1400x800.png">
                                <div class="card-body p-4">
                                    <p class="text-primary card-text mb-0">{status}</p>
                                    <h4 class="card-title">{title}</h4>
                                    <p class="card-text">{description}</p>
                                    <p class="card-text"><strong><span style="color: rgb(0, 0, 0);">Location: </span></strong>{description}</p><button class="btn btn-primary w-100" type="button">View Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                    <form class="row g-3 needs-validation" action="../BRBMS/assets/php/Auth.php" method="POST">
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
                    <form class="row g-3 needs-validation" action="../BRBMS/assets/php/Auth.php" method="POST">
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
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/theme.js"></script>
   
</body>

</html>