<?php
    // Hashing the passwords before insertion
    $password1 = password_hash('password1', PASSWORD_DEFAULT);
    $password2 = password_hash('password2', PASSWORD_DEFAULT);

    // Seed data for users table with hashed passwords
    $sql = "INSERT INTO users (username, password, email) VALUES
        ('user1', '$password1', 'user1@example.com'),
        ('user2', '$password2', 'user2@example.com')";

    if ($conn->query($sql) === TRUE) {
        echo "Users seeded successfully<br>";
    } else {
        echo "Error seeding users: " . $conn->error . "<br>";
    }
?>
