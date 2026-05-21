<?php
include("../config/db.php");

$problem_id = intval($_GET['id']);

// Get problem details
$sql = "SELECT problem_id, current_status, submitted_date 
        FROM problems 
        WHERE problem_id = $problem_id";

$problem = mysqli_fetch_assoc(mysqli_query($conn, $sql));

// Get remarks (timeline steps)
$remarks_sql = "SELECT user_level, remark_text, remark_date
                FROM problem_remarks
                WHERE problem_id = $problem_id
                ORDER BY remark_date ASC";

$remarks = mysqli_query($conn, $remarks_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Problem Timeline</title>

    <style>
        body { font-family: Arial; background:#f4f6f8; }

        .box {
            width: 70%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 6px;
        }

        .step {
            border-left: 4px solid #3498db;
            padding-left: 15px;
            margin-bottom: 20px;
        }

        .step h4 {
            margin: 0;
            color: #2c3e50;
        }

        .status {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
</head>

<body>

<div class="box">

    <h2>Problem Timeline</h2>

    <!-- DS submission -->
    <div class="step">
        <h4>DS Submitted</h4>
        <p><?php echo $problem['submitted_date']; ?></p>
    </div>

    <!-- All remarks -->
    <?php while ($row = mysqli_fetch_assoc($remarks)) { ?>
        <div class="step">
            <h4><?php echo $row['user_level']; ?></h4>
            <p><?php echo $row['remark_text']; ?></p>
            <small><?php echo $row['remark_date']; ?></small>
        </div>
    <?php } ?>

    <!-- Final status -->
    <div class="status">
        Current Status: <?php echo $problem['current_status']; ?>
    </div>

</div>

</body>
</html>
