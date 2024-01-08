<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../connection/index.php');

function loginUser($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    
    if ($stmt === false) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Verify hashed password using password_verify()
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id']; 
            return true; // Successful login
        }
    }
    
    return false; // Failed login
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($conn, $username, $password)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: ../index.php'); // Redirect to index.php on successful login
        exit;
    } else {
        header('Location: ../login.php?error=1'); // Redirect back to login page with error indicator
        exit;
    }
}

$conn->close();
?>
