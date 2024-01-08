<?php
    session_start();

    // Check if the user is not logged in, then redirect to login page
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php?error=error_auth');
        exit;
    }

    require_once('view/menu.php');

    require_once('connection/index.php'); // Assuming the path is correct for the connection file

    // Fetch data from the savings table
    $sql = "SELECT * FROM savings";
    $result = $conn->query($sql);

    // Calculate savings totals for each type
    $sqlTotals = "SELECT 
        SUM(CASE WHEN savings_type = 'general' AND transaction_type = 'in' THEN amount
            WHEN savings_type = 'general' AND transaction_type = 'out' THEN -amount ELSE 0 END) AS general_total,
        SUM(CASE WHEN savings_type = 'health' AND transaction_type = 'in' THEN amount
            WHEN savings_type = 'health' AND transaction_type = 'out' THEN -amount ELSE 0 END) AS health_total,
        SUM(CASE WHEN savings_type = 'education' AND transaction_type = 'in' THEN amount
            WHEN savings_type = 'education' AND transaction_type = 'out' THEN -amount ELSE 0 END) AS education_total
        FROM savings";

    $totalResult = $conn->query($sqlTotals);
    $savingsTotals = $totalResult->fetch_assoc();
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Pejuang Rupiah - Tabungan</title>
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
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Savings Amount</h5>
                        <p class="card-text"><?php echo $savingsTotals['general_total'] + $savingsTotals['health_total'] + $savingsTotals['education_total']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">General Savings Amount</h5>
                        <p class="card-text"><?php echo $savingsTotals['general_total']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Health Savings Amount</h5>
                        <p class="card-text"><?php echo $savingsTotals['health_total']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Education Savings Amount</h5>
                        <p class="card-text"><?php echo $savingsTotals['education_total']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <h2>Savings Data</h2>

        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#savingsModal">
            Add Savings
        </button>

        <!-- Modal -->
        <div class="modal fade" id="savingsModal" tabindex="-1" aria-labelledby="savingsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="savingsModalLabel">Add Savings</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for adding savings -->
                        <form action="controller/savings.php" method="POST">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="transactionType" class="form-label">Transaction Type</label>
                                <select class="form-select" id="transactionType" name="transactionType" required>
                                    <option value="in">Income</option>
                                    <option value="out">Expense</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="savingsType" class="form-label">Savings Type</label>
                                <select class="form-select" id="savingsType" name="savingsType" required>
                                    <option value="general">General</option>
                                    <option value="health">Health</option>
                                    <option value="education">Education</option>
                                    <!-- Add other options as needed -->
                                </select>
                            </div>
                            <!-- Add more input fields for description, date, etc., if required -->

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Amount</th>
                    <th>Savings Type</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <!-- Add more table headers as needed -->
                </tr>
            </thead>
            <tbody>
                <?php
                $totalRecords = $result->num_rows; // Total number of records
                if ($totalRecords > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>";
                        if ($row['transaction_type'] === 'in') {
                            echo "<span style='color: green;'>+" . $row['amount'] . "</span>";
                        } else {
                            echo "<span style='color: red;'>-" . $row['amount'] . "</span>";
                        }
                        echo "</td>";
                        echo "<td>" . $row['savings_type'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>"; // Assuming 'created_at' is the column name
                        // Add more table data cells based on the columns in your table
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
            echo "Total records: " . $totalRecords;
        ?>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
