<?php
session_start();
include("../config/db.php");

// Security check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Decision & History</title>

    <style>

        body{
            font-family:Arial;
            background:#f4f6f8;
        }

        .container{
            width:90%;
            margin:30px auto;
        }

        .header{
            background:#1b4f72;
            color:white;
            padding:20px;
            border-radius:5px;
        }

        .card{
            background:white;
            padding:20px;
            margin-top:20px;
            border-radius:5px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th{
            background:#34495e;
            color:white;
            padding:10px;
        }

        table td{
            border:1px solid #ccc;
            padding:10px;
        }

        .btn{
            background:#27ae60;
            color:white;
            padding:5px 10px;
            text-decoration:none;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h2>Decision & History</h2>

        <a href="dashboard.php"
           style="color:white;">

           Back to Dashboard

        </a>

    </div>

    <div class="card">

        <table>

            <tr>

                <th>Problem ID</th>
                <th>DS Division</th>
                <th>Village</th>
                <th>Problem Type</th>
                <th>Status</th>
                <th>Timeline</th>

            </tr>

            <?php

            $sql = "SELECT

                    p.problem_id,
                    p.problem_type,
                    p.current_status,

                    l.ds_division,
                    l.village

                    FROM problems p

                    JOIN land l
                    ON p.land_id = l.land_id

                    ORDER BY p.problem_id DESC";

            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_assoc($result)) {

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
                    <?php echo $row['current_status']; ?>
                </td>

                <td>

                    <a class="review-btn"

href="decision_view.php?id=
<?php echo $row['problem_id']; ?>">

View Details

</a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>