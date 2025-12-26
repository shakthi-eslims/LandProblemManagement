<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'DS') {
    header("Location: ../login.php");
    exit();
}

$ds_user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>DS – Final Decisions</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background: #1b4f72;
            color: white;
        }
        .view-btn {
            background: #2e86de;
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>

<div class="dashboard-container">
    <h2>Final Decisions / Problem History</h2>

    <table>
        <tr>
            <th>Problem ID</th>
            <th>DS Division</th>
            <th>Village</th>
            <th>Problem Type</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        $sql = "SELECT p.problem_id, p.problem_type, p.current_status,
                       l.ds_division, l.village
                FROM problems p
                JOIN land l ON p.land_id = l.land_id
                WHERE p.submitted_by = $ds_user_id
                  AND p.current_status IN ('DECIDED','PENDING_LCG')
                ORDER BY p.submitted_date DESC";

        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?php echo $row['problem_id']; ?></td>
            <td><?php echo $row['ds_division']; ?></td>
            <td><?php echo $row['village']; ?></td>
            <td><?php echo $row['problem_type']; ?></td>
            <td><?php echo $row['current_status']; ?></td>
            <td>
                <a class="view-btn"
                   href="decision_view.php?id=<?php echo $row['problem_id']; ?>">
                   View Details
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <br>
    <a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
