<?php
session_start();

// Security check
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

    <!-- Icons (Font Awesome CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="login-box" style="width:700px;">
    <h2 style="text-align:center;">Divisional Secretariat Dashboard</h2>

    <p style="text-align:center; color:#555;">
        Welcome! You are logged in as <b>DS Officer</b>
    </p>

    <!-- Dashboard Cards -->
    <div style="display:flex; gap:20px; margin-top:30px;">

        <!-- Add Land -->
        <a href="land_add.php" style="flex:1; text-decoration:none;">
            <div style="
                background:#eaf2f8;
                padding:30px;
                text-align:center;
                border-radius:8px;
                box-shadow:0 0 5px rgba(0,0,0,0.1);
            ">
                <i class="fa-solid fa-map-location-dot" style="font-size:40px; color:#2e86de;"></i>
                <h3 style="color:#2c3e50;">Add Land Details</h3>
                <p style="color:#555;">Enter new land information</p>
            </div>
        </a>

        <!-- Add Problem -->
        <a href="problem_add.php" style="flex:1; text-decoration:none;">
            <div style="
                background:#fef5e7;
                padding:30px;
                text-align:center;
                border-radius:8px;
                box-shadow:0 0 5px rgba(0,0,0,0.1);
            ">
                <i class="fa-solid fa-file-circle-exclamation" style="font-size:40px; color:#d68910;"></i>
                <h3 style="color:#2c3e50;">Add Land Problem</h3>
                <p style="color:#555;">Submit land related issue</p>
            </div>
        </a>

        <!-- Logout -->
        <a href="../logout.php" style="flex:1; text-decoration:none;">
            <div style="
                background:#fdecea;
                padding:30px;
                text-align:center;
                border-radius:8px;
                box-shadow:0 0 5px rgba(0,0,0,0.1);
            ">
                <i class="fa-solid fa-right-from-bracket" style="font-size:40px; color:#c0392b;"></i>
                <h3 style="color:#2c3e50;">Logout</h3>
                <p style="color:#555;">Exit the system safely</p>
            </div>
        </a>

    </div>
</div>

</body>
</html>
