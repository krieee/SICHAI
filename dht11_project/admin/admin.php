<?php
// Check if form fields are empty
if (empty($_POST["fname"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["password_confirmation"])) {
    die("All fields are required");
}

// Validate email format
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}

// Validate password length
if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters long");
}

// Validate password strength (at least one letter and one number)
if (!preg_match("/[a-zA-Z]/", $_POST["password"]) || !preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one letter and one number");
}

// Confirm password and password confirmation match
if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords do not match");
}

// Hash the password
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$role = 'user';

// Connect to database (assuming database.php returns mysqli object)
$mysqli = require __DIR__ . "/../database.php";

// Prepare SQL statement
$sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);

// Check if prepare() succeeded
if (!$stmt) {
    die("SQL error: " . $mysqli->error);
}

// Bind parameters and execute query
$stmt->bind_param("ssss", $_POST["fname"], $_POST["email"], $password_hash, $role);
if ($stmt->execute()) {
    // Redirect to login page or show success message
    header("Location: http://localhost/dht11_project/login/login.php");
    exit();
}
else {
    // Handle errors
    if ($mysqli->errno === 1062) {
        die("Email already taken");
    } else {
        die("Execute error: " . $stmt->error);
    }
}
?>
