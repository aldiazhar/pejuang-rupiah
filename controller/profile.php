<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is not logged in, then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php?error=error_auth');
    exit;
}

require_once('../connection/index.php'); // Include your database connection file

$user_id = $_SESSION['user_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['new_username'];
    $newEmail = $_POST['new_email'];
    $newPassword = $_POST['new_password'] ?? '';


    // Perform data validation
    if (empty($newUsername) || empty($newEmail)) {
        $_SESSION['error_message'] = "Username, email are required fields.";
        header("Location: ../profile.php?error=empty_fields");
        exit;
    }

    $sql = "UPDATE users SET username = ?, email = ?";
    $params = [$newUsername, $newEmail];

    if (!empty($newPassword)) {
        $sql .= ", password = ?";
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $params[] = $hashedPassword;
    }

    $sql .= " WHERE id = ?";
    $params[] = $user_id;

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        if (!empty($newPassword)) {
            $types = str_repeat('s', count($params)); // Assuming all parameters are strings
            $stmt->bind_param($types, ...$params);
        } else {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Profile updated successfully";
        } else {
            $_SESSION['error_message'] = "Failed to update profile: " . $conn->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
    }

    header('Location: ../profile.php');
    exit;
}

?>