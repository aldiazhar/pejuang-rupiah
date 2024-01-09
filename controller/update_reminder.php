<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is not logged in, then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php?error=error_auth');
    exit;
}

require_once('../connection/index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reminderId = $_POST['reminder_id'];
    $description = $_POST['reminder_description'];
    $numericThreshold = $_POST['numeric_threshold'];
    $status = $_POST['status'];

    // Perform validation or sanitation if necessary

    $sql = "UPDATE reminders SET reminder_description = ?, numeric_threshold = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sdsi", $description, $numericThreshold, $status, $reminderId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Reminder updated successfully";
        } else {
            $_SESSION['error_message'] = "Failed to update reminder: " . $conn->error;
            // Log or echo $conn->error for detailed debugging
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
        // Log or echo $conn->error for detailed debugging
    }

    $conn->close();

    // Redirect back to the previous page
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
?>
