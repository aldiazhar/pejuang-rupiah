<?php
    // Example: Checking if the user is logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $navbarContent = '
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Tabungan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reminder.php">Reminder</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="controller/logout.php">Logout</a>
                </li>
            </ul>';
    } else {
        $navbarContent = '
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>';
    }
?>