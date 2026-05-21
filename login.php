<?php
session_start();
include("config/db.php");

$message = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn,
        "SELECT id, username, password, user_level
         FROM users
         WHERE username=?");

    mysqli_stmt_bind_param($stmt, "s", $username);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        if ($password == $row['password']) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_level'] = $row['user_level'];

            // Redirect by role
            if ($row['user_level'] == 'DS') {

                header("Location: ds/dashboard.php");

            } elseif ($row['user_level'] == 'PROVINCE') {

                header("Location: province/dashboard.php");

            } elseif ($row['user_level'] == 'LCG') {

                header("Location: lcg/dashboard.php");

            } elseif ($row['user_level'] == 'ADMIN') {

                header("Location: admin/dashboard.php");

            }

            exit();

        } else {
            $message = "Invalid Password";
        }

    } else {
        $message = "User Not Found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

body{

    margin:0;
    padding:0;

    font-family:Arial;

    background:

    linear-gradient(
    rgba(0,0,0,0.5),
    rgba(0,0,0,0.5)
    ),

    url('assets/images/bg1.png');

    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;

    height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box{

    width:350px;

    background:rgba(255,255,255,0.95);

    padding:35px;

    border-radius:10px;

    box-shadow:0 0 15px rgba(0,0,0,0.3);
}

.login-box h2{

    text-align:center;
    margin-bottom:25px;
    color:#1b4f72;
}

.form-group{

    margin-bottom:15px;
}

.form-group label{

    display:block;
    margin-bottom:5px;
}

.form-group input{

    width:100%;
    padding:10px;

    border:1px solid #ccc;

    border-radius:4px;
}

.btn-login{

    width:100%;

    background:#2e86de;

    color:white;

    border:none;

    padding:12px;

    border-radius:5px;

    cursor:pointer;

    font-size:16px;
}

.btn-login:hover{

    background:#1b4f72;
}

</style>

</head>

<body>

<div class="login-box">

    <h2>Land Problem Management System</h2>

    <?php if ($message != "") { ?>
        <p style="color:red;"><?php echo $message; ?></p>
    <?php } ?>

    <form method="post">

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

</div>

</body>
</html>