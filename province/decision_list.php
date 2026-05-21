<?php

session_start();
include("../config/db.php");

// Security Check
if (!isset($_SESSION['user_id']) ||
    $_SESSION['user_level'] != 'PROVINCE') {

    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Get Province User Details

$user_query = mysqli_query(

$conn,

"SELECT

province_id

FROM users

WHERE id='$user_id'"

);

$user_data =
mysqli_fetch_assoc($user_query);

$province_id =
$user_data['province_id'];

?>

<!DOCTYPE html>
<html>
<head>

    <title>
        Province Decisions & History
    </title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

    <style>

        body{
            font-family:Arial;
            background:#f4f6f8;
        }

        .container{
            width:92%;
            margin:30px auto;
            background:white;
            padding:20px;
            border-radius:8px;
        }

        h2{
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th, td{
            border:1px solid #ccc;
            padding:10px;
            text-align:left;
        }

        th{
            background:#34495e;
            color:white;
        }

        .status-pending{
            color:#e67e22;
            font-weight:bold;
        }

        .status-lcg{
            color:#3498db;
            font-weight:bold;
        }

        .status-decided{
            color:#27ae60;
            font-weight:bold;
        }

        .view-btn{
            background:#2e86de;
            color:white;
            padding:6px 10px;
            text-decoration:none;
            border-radius:4px;
        }

        .back-btn{
            display:inline-block;
            margin-top:20px;
            background:#34495e;
            color:white;
            padding:10px 15px;
            text-decoration:none;
            border-radius:5px;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>
        Province Decisions & Full History
    </h2>

    <table>

        <tr>

            <th>Problem ID</th>

            <th>DS Division</th>

            <th>Village</th>

            <th>Problem Type</th>

            <th>Status</th>

            <th>Submitted Date</th>

            <th>Action</th>

        </tr>

        <?php

        $sql = "SELECT

                p.problem_id,
                p.problem_type,
                p.current_status,
                p.submitted_date,

                l.ds_division,
                l.village

                FROM problems p

                JOIN land l
                ON p.land_id = l.land_id

                WHERE p.province_id='$province_id'

                ORDER BY p.problem_id DESC";

        $result =
        mysqli_query($conn, $sql);

        while($row =
        mysqli_fetch_assoc($result)) {

        ?>

        <tr>

            <td>
                <?php echo $row['problem_id']; ?>
            </td>

            <td>
                <?php echo $row['ds_division']; ?>
            </td>

            <td>
                <?php echo $row['village']; ?>
            </td>

            <td>
                <?php echo $row['problem_type']; ?>
            </td>

            <td>

                <?php

                if($row['current_status']
                == 'PENDING_PLC') {

                    echo '<span class="status-pending">
                    PENDING PLC
                    </span>';

                }

                elseif($row['current_status']
                == 'PENDING_LCG') {

                    echo '<span class="status-lcg">
                    PENDING LCG
                    </span>';

                }

                elseif($row['current_status']
                == 'DECIDED') {

                    echo '<span class="status-decided">
                    DECIDED
                    </span>';

                }

                else {

                    echo $row['current_status'];
                }

                ?>

            </td>

            <td>
                <?php echo $row['submitted_date']; ?>
            </td>

            <td>

                <a class="view-btn"

               href="decision_view.php?id=
               <?php echo $row['problem_id']; ?>">

                View

                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

    <a href="dashboard.php"
       class="back-btn">

       ← Back to Dashboard

    </a>

</div>

</body>
</html>