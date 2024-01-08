<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../connection/index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null; // Get the user ID from the session

    $amount = $_POST['amount'];
    $transactionType = $_POST['transactionType'];
    $savingsType = $_POST['savingsType'];
    $savingsDescription = $_POST['description'];

    if ($user_id) {
        $sql = "INSERT INTO savings (amount, transaction_type, savings_type, user_id, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("issss", $amount, $transactionType, $savingsType, $user_id, $savingsDescription);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $_SESSION['success_message'] = "Savings added successfully";
            } else {
                // Get the specific MySQL error message
                $_SESSION['error_message'] = "Failed to add savings: " . $conn->error;
            }

            $stmt->close();
        } else {
            // Get the specific MySQL error message
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
