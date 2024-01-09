<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the user's total 'in' transaction type savings
$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    // Query to get total 'in' transaction type savings for the user
    $queryInSavings = "SELECT SUM(amount) AS total_in_savings FROM savings WHERE user_id = ? AND transaction_type = 'in'";
    $stmtInSavings = $conn->prepare($queryInSavings);

    if ($stmtInSavings) {
        $stmtInSavings->bind_param("i", $user_id);
        $stmtInSavings->execute();
        $resultInSavings = $stmtInSavings->get_result();

        if ($resultInSavings->num_rows > 0) {
            $rowInSavings = $resultInSavings->fetch_assoc();
            $totalInSavings = $rowInSavings['total_in_savings'];
        } else {
            $totalInSavings = 0; // Default value if no 'in' savings found
        }

        $stmtInSavings->close();
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
        // Handle the error accordingly
    }
} else {
    $_SESSION['error_message'] = "User ID not found";
    // Handle the error accordingly
}

// Get reminders with numeric_threshold values for 'in' transactions
$queryReminders = "SELECT id, numeric_threshold, reminder_description FROM reminders WHERE user_id = ? AND status = 'active'";
$stmtReminders = $conn->prepare($queryReminders);

if ($stmtReminders) {
    $stmtReminders->bind_param("i", $user_id);
    $stmtReminders->execute();
    $resultReminders = $stmtReminders->get_result();

    $alerts = []; // Array to store alerts

    while ($row = $resultReminders->fetch_assoc()) {
        $reminderId = $row['id'];
        $numericThreshold = $row['numeric_threshold'];
        $description = $row['reminder_description'];

        // Compare numeric_threshold with totalInSavings for each 'in' transaction reminder
        if ($totalInSavings >= $numericThreshold) {
            // Create alert messages and store in the alerts array
            $alertMessage = "Reminder: $description";
            $alerts[] = $alertMessage;
        }
    }

    $stmtReminders->close();
} else {
    $_SESSION['error_message'] = "Error: " . $conn->error;
    // Handle the error accordingly
}
?>