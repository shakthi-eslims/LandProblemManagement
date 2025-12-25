<?php
session_start();
include "config.php"; // contains your $conn connection

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ⚠️ For production, use prepared statements and password hashing.
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'ADMIN') {
            header("Location: admin/dashboard.php");
            exit();
        } else {
            echo "Login successful, but no dashboard defined for role: " . $user['role'];
        }
    } else {
        echo "Invalid login";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - Land Problem Management System</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
