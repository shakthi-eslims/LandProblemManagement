<?php
session_start();
include("config/db.php");

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, role FROM users WHERE username = ? AND password = ?"
    );

    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $role);

    if (mysqli_stmt_fetch($stmt)) {

        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_level'] = $role;

        // Redirect by role
        if ($role == 'DS') {
            header("Location: ds/dashboard.php");
        } elseif ($role == 'PROVINCE') {
            header("Location: province/dashboard.php");
        } elseif ($role == 'LCG') {
            header("Location: lcg/dashboard.php");
        } elseif ($role == 'ADMIN') {
            header("Location: admin/dashboard.php");
        } else {
            $error = "No dashboard defined for role: " . $role;
        }
        exit();

    } else {
        $error = "Invalid username or password";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Land Problem Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-box">
    <h2>Land Problem Management System</h2>

    <?php if ($error != "") { ?>
        <p style="color:red; text-align:center;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php } ?>

    <form method="post" action="">

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" name="login" class="btn-login">
            Login
        </button>

    </form>

    <div class="footer-text">
        © Land Commissioner General’s Department
    </div>
</div>

</body>
</html>
