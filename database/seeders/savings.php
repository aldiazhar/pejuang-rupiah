<?php
// Seed data for savings table with various transactions
$transactions = [
    ['user_id' => 1, 'amount' => 100, 'transaction_type' => 'in', 'savings_type' => 'general', 'description' => 'Incoming transaction 1'],
    ['user_id' => 1, 'amount' => 150, 'transaction_type' => 'out', 'savings_type' => 'health', 'description' => 'Outgoing transaction 1'],
    ['user_id' => 2, 'amount' => 200, 'transaction_type' => 'in', 'savings_type' => 'education', 'description' => 'Incoming transaction 2'],
    ['user_id' => 2, 'amount' => 50, 'transaction_type' => 'out', 'savings_type' => 'general', 'description' => 'Outgoing transaction 2'],
    ['user_id' => 1, 'amount' => 120, 'transaction_type' => 'in', 'savings_type' => 'general', 'description' => 'Incoming transaction 3'],
    ['user_id' => 2, 'amount' => 80, 'transaction_type' => 'out', 'savings_type' => 'health', 'description' => 'Outgoing transaction 3'],
    ['user_id' => 1, 'amount' => 90, 'transaction_type' => 'in', 'savings_type' => 'education', 'description' => 'Incoming transaction 4'],
    ['user_id' => 2, 'amount' => 70, 'transaction_type' => 'out', 'savings_type' => 'general', 'description' => 'Outgoing transaction 4'],
    ['user_id' => 1, 'amount' => 110, 'transaction_type' => 'in', 'savings_type' => 'health', 'description' => 'Incoming transaction 5'],
    ['user_id' => 2, 'amount' => 60, 'transaction_type' => 'out', 'savings_type' => 'education', 'description' => 'Outgoing transaction 5'],
    // Add more transactions...
];

foreach ($transactions as $transaction) {
    $user_id = $transaction['user_id'];
    $amount = $transaction['amount'];
    $transaction_type = $transaction['transaction_type'];
    $savings_type = $transaction['savings_type'];
    $description = $transaction['description'];

    $sql = "INSERT INTO savings (user_id, amount, transaction_type, savings_type, description)
            VALUES ('$user_id', '$amount', '$transaction_type', '$savings_type', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "Transaction added successfully<br>";
    } else {
        echo "Error adding transaction: " . $conn->error . "<br>";
    }
}
?>
