<?php
session_start();

// check login
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'DS') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DS Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="login-box">
    <h2>DS Dashboard</h2>

    <p>Welcome! You are logged in as <b>Divisional Secretary</b>.</p>

    <ul>
        <li><a href="land_add.php">Add Land Details</a></li>
        <li><a href="problem_add.php">Add Land Problem</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

</body>
</html>
