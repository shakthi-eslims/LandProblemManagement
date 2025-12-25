<?php
session_start();
include "../config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['save'])) {
    $name = $_POST['full_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (full_name, username, password, role)
            VALUES ('$name', '$username', '$password', '$role')";

    if ($conn->query($sql)) {
        echo "User created successfully";
    } else {
        echo "Error creating user";
    }
}
?>

<h2>Create User</h2>

<form method="post">
    Full Name:<br>
    <input type="text" name="full_name" required><br><br>

    Username:<br>
    <input type="text" name="username" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    Role:<br>
    <select name="role" required>
        <option value="DS">Divisional Secretariat</option>
        <option value="PROVINCE">Provincial Office</option>
        <option value="LCG">Land Commissioner General</option>
    </select><br><br>

    <button type="submit" name="save">Create User</button>
</form>

<br>
<a href="dashboard.php">Back to Dashboard</a>

