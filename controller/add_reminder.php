<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../connection/index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null; // Get the user ID from the session

    $reminderType = $_POST['reminderType'];
    $reminderDate = !empty($_POST['reminderDate']) ? $_POST['reminderDate'] : null;
    $numericThreshold = $_POST['numericThreshold'] ?? null;
    $reminderDescription = $_POST['reminderDescription'];
    $status = "active"; // Replace with the actual status

    if ($user_id) {
        $sql = "INSERT INTO reminders (user_id, reminder_type, reminder_date, numeric_threshold, reminder_description, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("isssss", $user_id, $reminderType, $reminderDate, $numericThreshold, $reminderDescription, $status);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $_SESSION['success_message'] = "Reminder set successfully";
            } else {
                $_SESSION['error_message'] = "Failed to set reminder: " . $conn->error;
            }

            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Error: " . $conn->error;
        }
    } else {
        $_SESSION['error_message'] = "User ID not found";
    }

    $conn->close();

    // Redirect back to the previous page
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
?>
