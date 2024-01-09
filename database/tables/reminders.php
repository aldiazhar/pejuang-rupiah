<?php
    // SQL to create reminders table with timestamps
    $sql = "CREATE TABLE IF NOT EXISTS reminders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        reminder_type ENUM('date', 'numeric') NOT NULL,
        reminder_date DATE NULL,
        numeric_threshold DECIMAL(10, 2) NULL,
        reminder_description TEXT,
        status VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Table 'reminders' created or already exists<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
?>
