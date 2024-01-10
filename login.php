<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php'); // Redirect to the index page
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h2>Login</h2>
        <?php
            session_start();

            // Check if there's a login error parameter in the URL
            if (isset($_GET['error']) && $_GET['error'] == 1) {
                echo '<p style="color: red;">Invalid username or password!</p>';
            }

            // Check if there's a login error parameter in the URL
            if (isset($_GET['error']) && $_GET['error'] == 'error_auth') {
                echo '<p style="color: red;">You need to login</p>';
            }
            
            // Rest of your login page content and form
        ?>
        <form action="controller/authentication.php" method="POST">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Login</button>
          <p>Don't have an account? <a href="register.php">Register</a></p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
