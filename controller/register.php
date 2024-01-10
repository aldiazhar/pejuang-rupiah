<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once('../connection/index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Perform data validation
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Username, email, and password are required fields.";
        header("Location: ../register.php?error=empty_fields");
        exit;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL query to insert user data into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Registration successful. You can now login.";
            header("Location: ../login.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Failed to register user: " . $conn->error;
            header("Location: ../register.php?error=mysql_error");
            exit;
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
        header("Location: ../register.php?error=mysql_error");
        exit;
    }

    $conn->close();
} else {
    header("Location: ../register.php");
    exit;
}
?>
