<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN') {
    header("Location: ../login.php");
    exit();
}
?>

<h2>Admin Dashboard</h2>

<ul>
    <li><a href="create_user.php">Create User</a></li>
    <li><a href="../logout.php">Logout</a></li>
</ul>
