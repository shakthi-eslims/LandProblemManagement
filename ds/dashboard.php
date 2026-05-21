<?php
session_start();
include("../config/db.php");

// Security check
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'DS') {
    header("Location: ../login.php");
    exit();
}

// Logged DS User
$user_id = $_SESSION['user_id'];

// Get DS User Area Details
$user_query = mysqli_query($conn,

"SELECT
 users.full_name,
 users.ds_id,
 ds_divisions.ds_name,
 districts.district_name,
 provinces.province_name

 FROM users

 LEFT JOIN ds_divisions
 ON users.ds_id = ds_divisions.ds_id

 LEFT JOIN districts
 ON users.district_id = districts.district_id

 LEFT JOIN provinces
 ON users.province_id = provinces.province_id

 WHERE users.id='$user_id'");

$user_data = mysqli_fetch_assoc($user_query);

$province_name = $user_data['province_name'];
$district_name = $user_data['district_name'];
$ds_name = $user_data['ds_name'];
$full_name = $user_data['full_name'];


// Total Problems
$total_query = mysqli_query($conn,

"SELECT COUNT(*) AS total

 FROM problems p

 JOIN land l
 ON p.land_id = l.land_id

 WHERE l.ds_id='" . $user_data['ds_id'] . "'");

$total_data = mysqli_fetch_assoc($total_query);

$total_problems = $total_data['total'];


// Pending PLC
$pending_query = mysqli_query($conn,

"SELECT COUNT(*) AS total

 FROM problems p

 JOIN land l
 ON p.land_id = l.land_id

 WHERE

 l.ds_id='" . $user_data['ds_id'] . "'

 AND

 p.current_status='PENDING_PLC'");

$pending_data = mysqli_fetch_assoc($pending_query);

$pending_total = $pending_data['total'];


// Decided
$decided_query = mysqli_query($conn,

"SELECT COUNT(*) AS total

 FROM problems p

 JOIN land l
 ON p.land_id = l.land_id

 WHERE

 l.ds_id='" . $user_data['ds_id'] . "'

 AND

 p.current_status='DECIDED'");

$decided_data = mysqli_fetch_assoc($decided_query);

$decided_total = $decided_data['total'];

?>

<!DOCTYPE html>
<html>
<head>

    <title>DS Dashboard</title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

    <style>

        body{
            font-family:Arial;
            background:#f4f6f8;
            margin:0;
        }

        .dashboard-container{
            width:92%;
            margin:30px auto;
        }

        .dashboard-header{
            background:#1b4f72;
            color:white;
            padding:25px;
            border-radius:8px;
        }

        .dashboard-header h2{
            margin:0;
        }

        .dashboard-header p{
            margin-top:10px;
        }

        .top-summary{
            display:flex;
            gap:20px;
            margin-top:25px;
            flex-wrap:wrap;
        }

        .summary-box{
            flex:1;
            min-width:220px;
            color:white;
            padding:20px;
            border-radius:8px;
        }

        .summary-box h2{
            margin:0;
            font-size:32px;
        }

        .summary-box p{
            margin-top:10px;
        }

        .blue{
            background:#3498db;
        }

        .green{
            background:#27ae60;
        }

        .orange{
            background:#e67e22;
        }

        .card-row{
            display:flex;
            gap:20px;
            margin-top:30px;
            flex-wrap:wrap;
        }

        .card{
            flex:1;
            min-width:240px;
            background:white;
            padding:25px;
            border-radius:8px;
            box-shadow:0 0 8px rgba(0,0,0,0.1);
            text-align:center;
        }

        .card h3{
            color:#2c3e50;
            margin-bottom:10px;
        }

        .card p{
            color:#555;
            font-size:14px;
        }

        .btn{
            display:inline-block;
            margin-top:15px;
            padding:10px 15px;
            color:white;
            text-decoration:none;
            border-radius:5px;
        }

        .btn-blue{
            background:#3498db;
        }

        .btn-green{
            background:#27ae60;
        }

        .btn-orange{
            background:#e67e22;
        }

        .btn-red{
            background:#c0392b;
        }

        .user-area{
            margin-top:15px;
            background:rgba(255,255,255,0.15);
            padding:10px;
            border-radius:5px;
        }

    </style>

</head>

<body>

<div class="dashboard-container">

    <!-- Header -->

    <div class="dashboard-header">

        <h2>Divisional Secretariat Dashboard</h2>

        <p>
            Land Problem Management System
        </p>

        <div class="user-area">

            <strong>User:</strong>
            <?php echo $full_name; ?>

            <br><br>

            <strong>Province:</strong>
            <?php echo $province_name; ?>

            |

            <strong>District:</strong>
            <?php echo $district_name; ?>

            |

            <strong>DS Division:</strong>
            <?php echo $ds_name; ?>

        </div>

    </div>


    <!-- Summary -->

    <div class="top-summary">

        <div class="summary-box blue">

            <h2>
                <?php echo $total_problems; ?>
            </h2>

            <p>Total Problems</p>

        </div>

        <div class="summary-box orange">

            <h2>
                <?php echo $pending_total; ?>
            </h2>

            <p>Pending Province Review</p>

        </div>

        <div class="summary-box green">

            <h2>
                <?php echo $decided_total; ?>
            </h2>

            <p>Final Decisions Received</p>

        </div>

    </div>


    <!-- Dashboard Cards -->

    <div class="card-row">

        <!-- Add Land -->

        <div class="card">

            <h3>🌍 Land Registry</h3>

            <p>
                Add and manage land details
            </p>

            <a href="land_add.php"
               class="btn btn-blue">

               Add Land

            </a>

        </div>


        <!-- Add Problem -->

        <div class="card">

            <h3>⚠ Land Problems</h3>

            <p>
                Submit land problems
                with recommendations
            </p>

            <a href="problem_add.php"
               class="btn btn-green">

               Add Problem

            </a>

        </div>


        <!-- Decisions -->

        <div class="card">

            <h3>📜 Decisions & History</h3>

            <p>
                View Province and
                LCG decisions
            </p>

            <a href="decision_list.php"
               class="btn btn-orange">

               View Decisions

            </a>

        </div>


        <!-- Logout -->

        <div class="card">

            <h3>🚪 Logout</h3>

            <p>
                Exit from the system safely
            </p>

            <a href="../logout.php"
               class="btn btn-red">

               Logout

            </a>

        </div>

    </div>

</div>

</body>
</html>