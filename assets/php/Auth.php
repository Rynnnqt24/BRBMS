<?php
session_start();
require_once '../../config/config.php'; // Include database connection
ini_set('display_errors', 1);
error_reporting(E_ALL);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {

    // Collect data from the form
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password for security
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $gender = $_POST['gender'];
    $user_role = $_POST['user_role'];

    try {
        // Prepare the SQL statement
        $query = $db->prepare("INSERT INTO users (username, password, full_name, email, contact_number, gender, user_role) 
                               VALUES (:username, :password, :full_name, :email, :contact_number, :gender, :user_role)");

        // Execute the query with parameters
        $query->execute([
            ':username' => $username,
            ':password' => $password,
            ':full_name' => $full_name,
            ':email' => $email,
            ':contact_number' => $contact_number,
            ':gender' => $gender,
            ':user_role' => $user_role
        ]);

        // Redirect or give feedback
        echo "<script>alert('Signup successful! Please login.'); window.location.href='/index.php';</script>";
    } catch (PDOException $e) {
        // Error handling
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Handle login
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields.';
        header('Location: /BRBMS/index.php');
        exit();
    }

    try {
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['user_role'];

            if ($user['user_role'] === 'owner') {
                header('Location: /BRBMS/admin/index.php');
            } elseif ($user['user_role'] === 'customer') {
                header('Location: /BRBMS/customer/index.php');
            }
            exit();
        } else {
            $_SESSION['error'] = 'Invalid username or password.';
            header('Location: /index.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'An error occurred. Please try again later.';
        header('Location: /index.php');
        exit();
    }
}
?>
