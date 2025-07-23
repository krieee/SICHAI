<?php
session_start();
include __DIR__ . '/../database.php';
$email = $_SESSION['user'];
$query = "SELECT * FROM users where email = '$email'";
$query_run = mysqli_query($mysqli, $query);
$row = mysqli_fetch_array($query_run);
$name = $row['name'];
$email = $row['email'];
$role = $row['role'];
?>
<!DOCTYPE html>
<lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <link rel="stylesheet" href="styles.css" />
        <title>Sichai User Dashboard</title>
    </head>

    <div>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar -->
            <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                <a class="navbar-brand" href="index.php">
                    <img src="http://localhost/dht11_project/logo2.png" width="190" height="70"
                        class="d-inline-block align-text-top" alt="Sichai Logo">
                    <!-- <span class="logo-text">Sichai</span> -->
                </a>
            </div>
                <div class="list-group list-group-flush my-3">
                    <a href="admindashboard.php"
                        class="list-group-item list-group-item-action bg-transparent second-text active"><i
                            class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href="profile.php"
                        class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                            class="fas fa-project-diagram me-2"></i>Profile
                    </a>
                    <a href="users.php"
                        class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                            class="fas fa-gift me-2"></i>Users</a>
                    <!-- <a href="users.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-gift me-2"></i>Settings</a> -->
                    <a href="logout.php"
                        class="list-group-item list-group-item-action bg-transparent text-danger fw-bold"><i
                            class="fas fa-power-off me-2"></i>Logout</a>
                </div>
            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">
                <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                        <h2 class="fs-2 m-0">Admin Profile</h2>
                    </div>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user me-2"></i><?php echo $_SESSION['name']; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                    <!-- <li><a class="dropdown-item" href="#">Settings</a></li> -->
                                    <li><a class="dropdown-item"
                                            href="http://localhost/dht11_project/login/login.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="card shadow-sm border-0 rounded-lg">
                            <div class="card-body pt-4">
                                <h5 class="card-title text-center mb-4">Admin Information</h5>
                                <form>
                                    <div class="mb-3 row">
                                        <label for="fullName" class="col-md-3 col-form-label fw-bold">Full Name:</label>
                                        <div class="col-md-9">
                                            <input type="text" readonly class="form-control-plaintext"
                                                id="fullName" value="<?php echo $name; ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="email" class="col-md-3 col-form-label fw-bold">Email:</label>
                                        <div class="col-md-9">
                                            <input type="email" readonly class="form-control-plaintext"
                                                id="email" value="<?php echo $email; ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="role" class="col-md-3 col-form-label fw-bold">Role:</label>
                                        <div class="col-md-9">
                                            <input type="text" readonly class="form-control-plaintext"
                                                id="role" value="<?php echo $role; ?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    </div>
    <!-- /#page-content-wrapper -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function () {
            el.classList.toggle("toggled");
        };
    </script>
    </body>

    </html>