<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'DS') {
    header("Location: ../login.php");
    exit();
}

$problem_id = intval($_GET['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Problem Full History</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .box {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .title {
            font-weight: bold;
            color: #1b4f72;
        }
    </style>
</head>

<body>

<div class="dashboard-container">

<?php
$sql = "SELECT p.problem_type, p.problem_description, p.current_status,
               l.*
        FROM problems p
        JOIN land l ON p.land_id = l.land_id
        WHERE p.problem_id = $problem_id";

$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
?>

<div class="box">
    <div class="title">Land Details</div>
    DS Division: <?php echo $data['ds_division']; ?><br>
    GN Division: <?php echo $data['gn_division']; ?><br>
    Village: <?php echo $data['village']; ?><br>
    Plan No: <?php echo $data['plan_number']; ?><br>
    Lot No: <?php echo $data['lot_number']; ?><br>
    Extent: <?php echo $data['extent']; ?><br>
</div>

<div class="box">
    <div class="title">Problem Details</div>
    Type: <?php echo $data['problem_type']; ?><br>
    Description: <?php echo $data['problem_description']; ?><br>
    Status: <?php echo $data['current_status']; ?>
</div>

<div class="box">
    <div class="title">Recommendations / Decisions</div>

    <?php
    $rsql = "SELECT user_level, remark_text, remark_date
             FROM problem_remarks
             WHERE problem_id = $problem_id
             ORDER BY remark_date";

    $rres = mysqli_query($conn, $rsql);

    while ($r = mysqli_fetch_assoc($rres)) {
        echo "<b>{$r['user_level']}:</b> {$r['remark_text']}<br>
              <small>{$r['remark_date']}</small><hr>";
    }
    ?>
</div>

<a href="decision_list.php">← Back to List</a>

</div>

</body>
</html>
