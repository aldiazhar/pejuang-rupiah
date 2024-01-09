<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is not logged in, then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php?error=error_auth');
    exit;
}

require_once('view/menu.php');
require_once('connection/index.php');

// Fetch reminders from the database
$sql = "SELECT * FROM reminders WHERE user_id = ?"; // Replace 'user_id' with your actual column name
$stmt = $conn->prepare($sql);
if ($stmt) {
    $user_id = $_SESSION['user_id'] ?? null;
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Pejuang Rupiah - Reminders</title>
</head>
<body>
    
    <!-- Navbar with dynamic content based on login status -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Pejuang Rupiah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php echo $navbarContent; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

        <div class="row">
            <div class="col">
                <?php
                    // Check for success or error messages in the session
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success" role="alert">';
                        echo $_SESSION['success_message'];
                        echo '</div>';
                        unset($_SESSION['success_message']); // Clear the message to prevent displaying it again
                    }

                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $_SESSION['error_message'];
                        echo '</div>';
                        unset($_SESSION['error_message']); // Clear the message to prevent displaying it again
                    }
                ?>
            </div>
        </div>
        <h2>Reminders</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reminder By</th>
                    <th>Numeric Threshold</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>Reminder By: " . $row['user_id'] . "</td>";
                            echo "<td>Numeric Threshold: " . $row['numeric_threshold'] . "</td>";
                            echo "<td>Status: " . $row['status'] ." </td>";
                            echo "<td>";
                            // Add update button triggering the modal
                            echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal' . $row['id'] . '">Update</button>';
                            echo "</td>";
                            echo "</tr>";

                            // Modal for update
                            echo '<div class="modal fade" id="updateModal' . $row['id'] . '" tabindex="-1" aria-labelledby="updateModalLabel' . $row['id'] . '" aria-hidden="true">';
                            echo '<div class="modal-dialog">';
                            echo '<div class="modal-content">';
                            echo '<div class="modal-header">';
                            echo '<h5 class="modal-title" id="updateModalLabel' . $row['id'] . '">Update Reminder</h5>';
                            echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                            echo '</div>';
                            echo '<div class="modal-body">';
                            // Add form for updating the reminder
                            echo '<form action="controller/update_reminder.php" method="POST">';
                            echo '<input type="hidden" name="reminder_id" value="' . $row['id'] . '">'; // Hidden input for the reminder ID
                            echo '<div class="mb-3">';
                            echo '<label for="reminder_description' . $row['id'] . '" class="form-label">Description</label>';
                            echo '<textarea class="form-control" id="reminder_description' . $row['id'] . '" name="reminder_description" rows="3">' . $row['reminder_description'] . '</textarea>';
                            echo '</div>';
                            echo '<div class="mb-3">';
                            echo '<label for="numeric' . $row['id'] . '" class="form-label">Numeric Threshold</label>';
                            echo '<input type="number" class="form-control" id="numeric' . $row['id'] . '" name="numeric_threshold" value="' . $row['numeric_threshold'] . '">';
                            echo '</div>';
                            echo '<div class="mb-3">';
                            echo '<label for="status' . $row['id'] . '" class="form-label">Status</label>';
                            echo '<select class="form-select" id="status' . $row['id'] . '" name="status">';
                            echo '<option value="active"' . ($row['status'] === 'active' ? ' selected' : '') . '>Active</option>';
                            echo '<option value="inactive"' . ($row['status'] === 'inactive' ? ' selected' : '') . '>Inactive</option>';
                            echo '</select>';
                            echo '</div>';
                            echo '<div class="modal-footer">';
                            echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
                            echo '<button type="submit" class="btn btn-primary">Save changes</button>';
                            echo '</div>';
                            echo '</form>'; // Close the form
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "<tr><td colspan='5'>No reminders found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
