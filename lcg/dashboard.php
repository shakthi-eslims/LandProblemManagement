<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'LCG') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LCG Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .dashboard-container {
            width: 95%;
            margin: 20px auto;
        }

        .dashboard-header {
            background: #1b4f72;
            color: white;
            padding: 20px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-btn {
            background: #c0392b;
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: white;
        }

        table th {
            background: #34495e;   /* FIX: NOT WHITE */
            color: white;          /* FIX */
            padding: 10px;
            border: 1px solid #ccc;
        }

        table td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        .action-btn {
            background: #27ae60;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">
        <div>
            <h2>Land Commissioner General Dashboard</h2>
            <p>Final Review & Decision</p>
        </div>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <h3 style="margin-top:30px;">Land Problems Forwarded by Province</h3>

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
                WHERE p.current_status = 'PENDING_LCG'
                ORDER BY p.submitted_date DESC";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            echo "<tr><td colspan='6'>No problems forwarded to LCG yet.</td></tr>";
        }

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?php echo $row['problem_id']; ?></td>
            <td><?php echo $row['ds_division']; ?></td>
            <td><?php echo $row['village']; ?></td>
            <td><?php echo $row['problem_type']; ?></td>
            <td><?php echo $row['current_status']; ?></td>
            <td>
                <a class="action-btn" 
                   href="review_problem.php?id=<?php echo $row['problem_id']; ?>">
                   Review
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>
