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

    <style>
        .dashboard-container {
            width: 90%;
            margin: 30px auto;
        }

        .dashboard-header {
            background: #1b4f72;
            color: white;
            padding: 20px;
            border-radius: 6px;
        }

        .card-row {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .card {
            flex: 1;
            min-width: 220px;
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .card p {
            color: #555;
            font-size: 14px;
        }

        .btn-login {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: #2e86de;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-green {
            background: #27ae60;
        }

        .btn-orange {
            background: #e67e22;
        }

        .btn-red {
            background: #c0392b;
        }
    </style>
</head>

<body>

<div class="dashboard-container">

    <!-- Header -->
    <div class="dashboard-header">
        <h2>Divisional Secretariat Dashboard</h2>
        <p>Land Problem Management System</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="card-row">

        <!-- Add Land -->
        <div class="card">
            <h3>Land Registry</h3>
            <p>Add and manage land details</p>
            <a href="land_add.php" class="btn-login">Add Land Details</a>
        </div>

        <!-- Add Problem -->
        <div class="card">
            <h3>Land Problems</h3>
            <p>Submit land problems with recommendation</p>
            <a href="problem_add.php" class="btn-login btn-green">
                Add Land Problem
            </a>
        </div>

        <!-- View Decisions / History -->
        <div class="card">
            <h3>Decisions & History</h3>
            <p>View Province & LCG decisions</p>

            <!-- ✅ YOUR REQUESTED LINK -->
            <a href="decision_list.php" class="btn-login btn-orange">
                View Decisions / History
            </a>
        </div>

        <!-- Logout -->
        <div class="card">
            <h3>Logout</h3>
            <p>Exit from the system safely</p>
            <a href="../logout.php" class="btn-login btn-red">
                Logout
            </a>
        </div>

    </div>

</div>

</body>
</html>
